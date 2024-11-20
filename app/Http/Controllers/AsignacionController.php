<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\AsignacionDetalle;
use App\Models\AsignacionEmpresa;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AsignacionController extends Controller
{
    public $validacion = [
        "contrato_id" => "required",
    ];

    public $mensajes = [
        "contrato_id.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Asignacions/Index");
    }

    public function listado()
    {
        $asignacions = Asignacion::select("asignacions.*")->get();
        return response()->JSON([
            "asignacions" => $asignacions
        ]);
    }

    public function api(Request $request)
    {
        $asignacions = Asignacion::with(["asignacion_detalles", "contrato"])->select("asignacions.*");
        $asignacions = $asignacions->get();
        return response()->JSON(["data" => $asignacions]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $asignacions = Asignacion::select("asignacions.*");

        if (trim($search) != "") {
            $asignacions->where("nombre", "LIKE", "%$search%");
        }

        $asignacions = $asignacions->paginate($request->itemsPerPage);
        return response()->JSON([
            "asignacions" => $asignacions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $asignacion_detalles = $request->asignacion_detalles;
        $errors = [];
        foreach ($asignacion_detalles as $key => $item) {

            if (!isset($item["asignacion_empresas"]) || count($item["asignacion_empresas"]) == 0) {
                $errors["asignacion_detalles" . $key] = "Debes agregar al menos 1 registro";
            } else {
                foreach ($item["asignacion_empresas"] as $item_empresa) {
                    if (!$item_empresa["empresa_id"]) {
                        $errors["asignacion_detalles" . $key] = "Debes seleccionar una empresa/asociación en cada fila";
                    }

                    if (!$item_empresa["p_adjudicacion"] || $item_empresa["p_adjudicacion"] < 1 || !$item_empresa["cantidad"] || $item_empresa["cantidad"] < 1 || !$item_empresa["cantidad_entero"] || $item_empresa["cantidad_entero"] < 1) {
                        $errors["asignacion_detalles_can" . $key] = "Los valores númericos no pueden estar vacios y tampoco pueden ser menores a 1 ó mayores a 100";
                    }
                }
            }
        }

        if (count($errors) > 0) {
            return throw ValidationException::withMessages($errors);
        }

        DB::beginTransaction();
        try {
            // crear la asignacion
            $request["fecha_registro"] = date("Y-m-d");
            $nueva_asignacion = Asignacion::create(array_map('mb_strtoupper', $request->except(["asignacion_detalles", "eliminados"])));

            foreach ($asignacion_detalles as $key => $item) {
                // nueva asignacion_detalle
                $asignacion_detalle = $nueva_asignacion->asignacion_detalles()->create([
                    "contrato_detalle_id" => $item["contrato_detalle_id"]
                ]);
                foreach ($item["asignacion_empresas"] as $item_empresa) {
                    // registrar asignacion_empresas
                    $asignacion_detalle->asignacion_empresas()->create([
                        "empresa_id" => $item_empresa["empresa_id"],
                        "p_adjudicacion" => $item_empresa["p_adjudicacion"],
                        "cantidad" => $item_empresa["cantidad"],
                        "cantidad_entero" => $item_empresa["cantidad_entero"],
                    ]);
                }
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nueva_asignacion, "asignacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'modulo' => 'ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("asignacions.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Asignacion $asignacion)
    {
        return response()->JSON($asignacion->load(["asignacion_detalles.contrato_detalle.proveedor", "asignacion_detalles.contrato_detalle.producto", "asignacion_detalles.asignacion_empresas.empresa", "contrato"]));
    }

    public function update(Asignacion $asignacion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $asignacion_detalles = $request->asignacion_detalles;
        $errors = [];
        foreach ($asignacion_detalles as $key => $item) {

            if (!isset($item["asignacion_empresas"]) || count($item["asignacion_empresas"]) == 0) {
                $errors["asignacion_detalles" . $key] = "Debes agregar al menos 1 registro";
            } else {
                foreach ($item["asignacion_empresas"] as $item_empresa) {
                    if (!$item_empresa["empresa_id"]) {
                        $errors["asignacion_detalles" . $key] = "Debes seleccionar una empresa/asociación en cada fila";
                    }

                    if (!$item_empresa["p_adjudicacion"] || $item_empresa["p_adjudicacion"] < 1 || !$item_empresa["cantidad"] || $item_empresa["cantidad"] < 1 || !$item_empresa["cantidad_entero"] || $item_empresa["cantidad_entero"] < 1) {
                        $errors["asignacion_detalles_can" . $key] = "Los valores númericos no pueden estar vacios y tampoco pueden ser menores a 1 ó mayores a 100";
                    }
                }
            }
        }

        if (count($errors) > 0) {
            return throw ValidationException::withMessages($errors);
        }

        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($asignacion, "asignacions");
            $asignacion->update(array_map('mb_strtoupper', $request->except(["asignacion_detalles", "eliminados"])));

            if ($request->eliminados) {
                foreach ($request->eliminados  as $value) {
                    $asignacion_empresa = AsignacionEmpresa::find($value);
                    $asignacion_empresa->delete();
                }
            }

            foreach ($asignacion_detalles as $key => $item) {
                // nueva asignacion_detalle
                $asignacion_detalle = AsignacionDetalle::find($item["id"]);
                foreach ($item["asignacion_empresas"] as $item_empresa) {
                    if ($item_empresa["id"] == 0) {
                        // registrar asignacion_empresas
                        $asignacion_detalle->asignacion_empresas()->create([
                            "empresa_id" => $item_empresa["empresa_id"],
                            "p_adjudicacion" => $item_empresa["p_adjudicacion"],
                            "cantidad" => $item_empresa["cantidad"],
                            "cantidad_entero" => $item_empresa["cantidad_entero"],
                        ]);
                    } else {
                        // actualizar
                        $asignacion_empresa = AsignacionEmpresa::find($item_empresa["id"]);
                        $asignacion_empresa->update([
                            "empresa_id" => $item_empresa["empresa_id"],
                            "p_adjudicacion" => $item_empresa["p_adjudicacion"],
                            "cantidad" => $item_empresa["cantidad"],
                            "cantidad_entero" => $item_empresa["cantidad_entero"],
                        ]);
                    }
                }
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($asignacion, "asignacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("asignacions.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Asignacion $asignacion)
    {
        DB::beginTransaction();
        try {
            foreach ($asignacion->asignacion_detalles as $asignacion_detalle) {
                $asignacion_detalle->asignacion_empresas()->delete();
                $asignacion_detalle->delete();
            }

            $asignacion->asignacion_detalles()->delete();
            $datos_original = HistorialAccion::getDetalleRegistro($asignacion, "asignacions");
            $asignacion->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'modulo' => 'ASIGNACIÓN DE EMPRESA/SOCIEDAD',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
