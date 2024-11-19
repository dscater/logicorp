<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\HistorialAccion;
use App\Models\Programacion;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ConductorController extends Controller
{
    public $validacion = [
        "nombre" => "required",
        "paterno" => "required",
        "ci" => "required|numeric",
        "ci_exp" => "required",
        "nacionalidad" => "required",
        "fecha_nac" => "required|date",
        "sexo" => "required",
        "estado_civil" => "required",
        "nro_licencia" => "required",
        "categoria" => "required",
        "fecha_vencimiento" => "required",
        "fono" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "paterno.required" => "Este campo es obligatorio",
        "ci.required" => "Este campo es obligatorio",
        "ci.numeric" => "Debes ingresar un valor númerico",
        "ci_exp.required" => "Este campo es obligatorio",
        "nacionalidad.required" => "Este campo es obligatorio",
        "fecha_nac.required" => "Este campo es obligatorio",
        "sexo.required" => "Este campo es obligatorio",
        "estado_civil.required" => "Este campo es obligatorio",
        "nro_licencia.required" => "Este campo es obligatorio",
        "categoria.required" => "Este campo es obligatorio",
        "fecha_vencimiento.required" => "Este campo es obligatorio",
        "fono.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Conductors/Index");
    }

    public function listado()
    {
        $conductors = Conductor::select("conductors.*")->get();
        return response()->JSON([
            "conductors" => $conductors
        ]);
    }

    public function api(Request $request)
    {
        $conductors = Conductor::select("conductors.*");
        $conductors = $conductors->get();
        return response()->JSON(["data" => $conductors]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $conductors = Conductor::select("conductors.*");

        if (trim($search) != "") {
            $conductors->where("nombre", "LIKE", "%$search%");
        }

        $conductors = $conductors->paginate($request->itemsPerPage);
        return response()->JSON([
            "conductors" => $conductors
        ]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }
        if (!$request->fecha_emision) {
            unset($request["fecha_emision"]);
        }

        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear el Conductor
            $request["fecha_registro"] = date("Y-m-d");
            $nuevo_conductor = Conductor::create(array_map('mb_strtoupper', $request->except("foto")));

            if (!$request->fecha_emision) {
                $nuevo_conductor->fecha_emision = null;
            }

            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_conductor->id . '.' . $file->getClientOriginalExtension();
                $nuevo_conductor->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $nuevo_conductor->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_conductor, "conductors");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN CONDUCTOR',
                'datos_original' => $datos_original,
                'modulo' => 'CONDUCTORES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("conductors.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Conductor $conductor)
    {
        return response()->JSON($conductor);
    }

    public function update(Conductor $conductor, Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }

        if (!$request->fecha_emision) {
            unset($request["fecha_emision"]);
        }
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($conductor, "conductors");
            $conductor->update(array_map('mb_strtoupper', $request->except("foto")));

            if ($request->hasFile('foto')) {
                $antiguo = $conductor->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/users/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $conductor->id . '.' . $file->getClientOriginalExtension();
                $conductor->foto = $nom_foto;
                $file->move(public_path() . '/imgs/users/', $nom_foto);
            }
            $conductor->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($conductor, "conductors");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN CONDUCTOR',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CONDUCTORES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("conductors.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Conductor $conductor)
    {
        DB::beginTransaction();
        try {
            $usos = Vehiculo::where("conductor_id", $conductor->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }
            $usos = Programacion::where("conductor_id", $conductor->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($conductor, "conductors");
            $conductor->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN CONDUCTOR',
                'datos_original' => $datos_original,
                'modulo' => 'CONDUCTORES',
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
