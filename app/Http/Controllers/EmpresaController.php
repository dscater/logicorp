<?php

namespace App\Http\Controllers;

use App\Models\AsignacionDetalle;
use App\Models\Empresa;
use App\Models\HistorialAccion;
use App\Models\Programacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public $validacion = [
        "razon_social" => "required|min:1",
        "nit" => "required|min:1",
        "nom_representante" => "required|min:1",
        "ap_representante" => "required|min:1",
        "fono" => "required|min:1",
        "tipo" => "required",
    ];

    public $mensajes = [
        "razon_social.required" => "Este campo es obligatorio",
        "razon_social.min" => "Debes ingresar al menos :min caracteres",
        "nit.required" => "Este campo es obligatorio",
        "nit.min" => "Debes ingresar al menos :min caracteres",
        "nom_representante.required" => "Este campo es obligatorio",
        "nom_representante.min" => "Debes ingresar al menos :min caracteres",
        "ap_representante.required" => "Este campo es obligatorio",
        "ap_representante.min" => "Debes ingresar al menos :min caracteres",
        "fono.required" => "Este campo es obligatorio",
        "fono.min" => "Debes ingresar al menos :min caracteres",
        "tipo.required" => "Este campo es obligatorio",
        "tipo.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Empresas/Index");
    }

    public function listado(Request $request)
    {
        $empresas = Empresa::select("empresas.*");

        if (isset($request->tipo) && $request->tipo) {
            $empresas->where("tipo", $request->tipo);
        }

        $empresas  = $empresas->get();
        return response()->JSON([
            "empresas" => $empresas
        ]);
    }

    public function api(Request $request)
    {
        $empresas = Empresa::select("empresas.*");
        $empresas = $empresas->get();
        return response()->JSON(["data" => $empresas]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $empresas = Empresa::select("empresas.*");

        if (trim($search) != "") {
            $empresas->where("razon_social", "LIKE", "%$search%");
        }

        $empresas = $empresas->paginate($request->itemsPerPage);
        return response()->JSON([
            "empresas" => $empresas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear la empresa
            $request["fecha_registro"] = date("Y-m-d");
            $nuevo_empresa = Empresa::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_empresa, "empresas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UNA EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'modulo' => 'EMPRESA/SOCIEDAD',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("empresas.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Empresa $empresa)
    {
        return response()->JSON($empresa);
    }

    public function update(Empresa $empresa, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($empresa, "empresas");
            $empresa->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($empresa, "empresas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UNA EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'EMPRESA/SOCIEDAD',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("empresas.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Empresa $empresa)
    {
        DB::beginTransaction();
        try {
            $usos = AsignacionDetalle::where("empresa_id", $empresa->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = Programacion::where("empresa_id", $empresa->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($empresa, "empresas");
            $empresa->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UNA EMPRESA/SOCIEDAD',
                'datos_original' => $datos_original,
                'modulo' => 'EMPRESA/SOCIEDAD',
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
