<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Contrato;
use App\Models\ContratoDetalle;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ContratoController extends Controller
{
    public $validacion = [
        "nro_lote" => "required",
        "empresa_id" => "required",
        "p_asignado" => "required|numeric|min:1|max:100",
        "contrato_detalles" => "required|array|min:1",
    ];

    public $mensajes = [
        "nro_lote.required" => "Este campo es obligatorio",
        "empresa_id.required" => "Este campo es obligatorio",
        "p_asignado.required" => "Este campo es obligatorio",
        "p_asignado.required" => "Debes ingresar un valor númerico",
        "p_asignado.min" => "El valor mínimo es de :min",
        "p_asignado.max" => "El valor maximo es de :max",
        "contrato_detalles.required" => "Este campo es obligatorio",
        "contrato_detalles.min" => "Debes agregar al menos :min detalle",
    ];

    public function index()
    {
        return Inertia::render("Contratos/Index");
    }

    public function listado()
    {
        $contratos = Contrato::select("contratos.*")->get();
        return response()->JSON([
            "contratos" => $contratos
        ]);
    }

    public function api(Request $request)
    {
        $contratos = Contrato::with(["contrato_detalles", "empresa"])->select("contratos.*");
        $contratos = $contratos->get();
        return response()->JSON(["data" => $contratos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $contratos = Contrato::select("contratos.*");

        if (trim($search) != "") {
            $contratos->where("nombre", "LIKE", "%$search%");
        }

        $contratos = $contratos->paginate($request->itemsPerPage);
        return response()->JSON([
            "contratos" => $contratos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $contrato_detalles = $request->contrato_detalles;
        $errors = [];
        foreach ($contrato_detalles as $item) {
            if (!$item["proveedor_id"] || !$item["producto_id"] || trim($item["tramo"]) == "" || trim($item["frontera"]) == "") {
                $errors["contrato_detalles"] = "Debes completar todos los campos del detalle";
            }

            if (!$item["cantidad"] || $item["cantidad"] < 1) {
                $errors["contrato_detalles_can"] = "El valor de cantidad no puede ser menor a 1";
            }
        }

        if (count($errors) > 0) {
            return throw ValidationException::withMessages($errors);
        }

        DB::beginTransaction();
        try {
            // crear el contrato
            $a_cod = Contrato::getCodigoNuevoContrato();
            $request["codigo"] = $a_cod[0];
            $request["nro_cod"] = $a_cod[1];
            $request["fecha_registro"] = date("Y-m-d");
            $nuevo_contrato = Contrato::create(array_map('mb_strtoupper', $request->except(["contrato_detalles", "eliminados"])));

            foreach ($contrato_detalles as $cd) {
                $nuevo_contrato->contrato_detalles()->create([
                    "proveedor_id" => $cd["proveedor_id"],
                    "producto_id" => $cd["producto_id"],
                    "tramo" => mb_strtoupper($cd["tramo"]),
                    "frontera" => mb_strtoupper($cd["frontera"]),
                    "cantidad" => $cd["cantidad"],
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_contrato, "contratos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN CONTRATO',
                'datos_original' => $datos_original,
                'modulo' => 'CONTRATOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("contratos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Contrato $contrato)
    {
        return response()->JSON($contrato->load(["contrato_detalles.proveedor","contrato_detalles.producto", "empresa"]));
    }

    public function update(Contrato $contrato, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        $contrato_detalles = $request->contrato_detalles;
        $errors = [];
        foreach ($contrato_detalles as $item) {
            if (!$item["proveedor_id"] || !$item["producto_id"] || trim($item["tramo"]) == "" || trim($item["frontera"] == "")) {
                $errors["contrato_detalles"] = "Debes completar todos los campos del detalle";
            }

            if (!$item["cantidad"] || $item["cantidad"] < 1) {
                $errors["contrato_detalles_can"] = "El valor de cantidad no puede ser menor a 1";
            }
        }

        if (count($errors) > 0) {
            return throw ValidationException::withMessages($errors);
        }

        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($contrato, "contratos");
            $contrato->update(array_map('mb_strtoupper', $request->except(["contrato_detalles", "eliminados"])));

            if ($request->eliminados) {
                foreach ($request->eliminados  as $value) {
                    $contrato_detalle = ContratoDetalle::find($value);
                    $contrato_detalle->delete();
                }
            }

            foreach ($contrato_detalles as $cd) {
                if ($cd["id"] == 0) {
                    $contrato->contrato_detalles()->create([
                        "proveedor_id" => $cd["proveedor_id"],
                        "producto_id" => $cd["producto_id"],
                        "tramo" => mb_strtoupper($cd["tramo"]),
                        "frontera" => mb_strtoupper($cd["frontera"]),
                        "cantidad" => $cd["cantidad"],
                    ]);
                } else {
                    $contrato_detalle = ContratoDetalle::find($cd["id"]);
                    $contrato_detalle->update([
                        "proveedor_id" => $cd["proveedor_id"],
                        "producto_id" => $cd["producto_id"],
                        "tramo" => mb_strtoupper($cd["tramo"]),
                        "frontera" => mb_strtoupper($cd["frontera"]),
                        "cantidad" => $cd["cantidad"],
                    ]);
                }
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($contrato, "contratos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN CONTRATO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CONTRATOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("contratos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Contrato $contrato)
    {
        DB::beginTransaction();
        try {
            $usos = Asignacion::where("contrato_id", $contrato->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $contrato->contrato_detalles()->delete();
            $datos_original = HistorialAccion::getDetalleRegistro($contrato, "contratos");
            $contrato->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN CONTRATO',
                'datos_original' => $datos_original,
                'modulo' => 'CONTRATOS',
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
