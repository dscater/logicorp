<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Pago;
use App\Models\Programacion;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProgramacionController extends Controller
{
    public $validacion = [
        "contrato_id" => "required",
        "empresa_id" => "required",
        "asociacion_id" => "required",
        "producto_id" => "required",
        "proveedor_id" => "required",
        "vehiculo_id" => "required",
        "conductor_id" => "required",
        "origen_destino" => "required",
        "frontera" => "required",
        "fecha_programacion" => "required|date",
    ];

    public $mensajes = [
        "contrato_id.required" => "Este campo es obligatorio",
        "empresa_id.required" => "Este campo es obligatorio",
        "asociacion_id.required" => "Este campo es obligatorio",
        "producto_id.required" => "Este campo es obligatorio",
        "proveedor_id.required" => "Este campo es obligatorio",
        "vehiculo_id.required" => "Este campo es obligatorio",
        "conductor_id.required" => "Este campo es obligatorio",
        "origen_destino.required" => "Este campo es obligatorio",
        "frontera.required" => "Este campo es obligatorio",
        "fecha_programacion.required" => "Este campo es obligatorio",
        "fecha_programacion.date" => "Debes ingresar una fecha valida",
    ];

    public function index()
    {
        return Inertia::render("Programacions/Index");
    }

    public function listado()
    {
        $programacions = Programacion::select("programacions.*")->get();
        return response()->JSON([
            "programacions" => $programacions
        ]);
    }

    public function api(Request $request)
    {
        $programacions = Programacion::with(["contrato", "empresa", "asociacion", "producto", "proveedor", "vehiculo", "conductor"])->select("programacions.*");
        $programacions = $programacions->get();
        return response()->JSON(["data" => $programacions]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $programacions = Programacion::select("programacions.*");

        if (trim($search) != "") {
            $programacions->where("nombre", "LIKE", "%$search%");
        }

        $programacions = $programacions->paginate($request->itemsPerPage);
        return response()->JSON([
            "programacions" => $programacions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear el programacion
            $request["fecha_registro"] = date("Y-m-d");
            $nuevo_programacion = Programacion::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_programacion, "programacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA PROGRAMACIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'PROGRAMACIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("programacions.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Programacion $programacion)
    {
        return response()->JSON($programacion);
    }

    public function update(Programacion $programacion, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($programacion, "programacions");
            $programacion->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($programacion, "programacions");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA PROGRAMACIÓN',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PROGRAMACIÓN',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("programacions.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Programacion $programacion)
    {
        DB::beginTransaction();
        try {
            $usos = Viaje::where("programacion_id", $programacion->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = Pago::where("programacion_id", $programacion->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($programacion, "programacions");
            $programacion->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA PROGRAMACIÓN',
                'datos_original' => $datos_original,
                'modulo' => 'PROGRAMACIÓN',
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
