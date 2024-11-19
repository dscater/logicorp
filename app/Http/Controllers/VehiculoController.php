<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Programacion;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class VehiculoController extends Controller
{
    public $validacion = [
        "marca" => "required",
        "modelo" => "required",
        "anio" => "required",
        "placa" => "required",
        "nro_chasis" => "required",
        "color" => "required",
        "volumen_tanque" => "required",
        "conductor_id" => "required",
    ];

    public $mensajes = [
        "marca.required" => "Este campo es obligatorio",
        "modelo.required" => "Este campo es obligatorio",
        "anio.required" => "Este campo es obligatorio",
        "placa.required" => "Este campo es obligatorio",
        "nro_chasis.required" => "Este campo es obligatorio",
        "color.required" => "Este campo es obligatorio",
        "volumen_tanque.required" => "Este campo es obligatorio",
        "conductor_id.required" => "Este campo es obligatorio",
    ];


    public function index()
    {
        return Inertia::render("Vehiculos/Index");
    }

    public function listado()
    {
        $vehiculos = Vehiculo::select("vehiculos.*")->get();
        return response()->JSON([
            "vehiculos" => $vehiculos
        ]);
    }

    public function api(Request $request)
    {
        $vehiculos = Vehiculo::with(["conductor"])->select("vehiculos.*");
        $vehiculos = $vehiculos->get();
        return response()->JSON(["data" => $vehiculos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $vehiculos = Vehiculo::select("vehiculos.*");

        if (trim($search) != "") {
            $vehiculos->where("nombre", "LIKE", "%$search%");
        }

        $vehiculos = $vehiculos->paginate($request->itemsPerPage);
        return response()->JSON([
            "vehiculos" => $vehiculos
        ]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }

        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear el Vehiculo
            $request["fecha_registro"] = date("Y-m-d");
            $nuevo_vehiculo = Vehiculo::create(array_map('mb_strtoupper', $request->except("foto")));

            if ($request->hasFile('foto')) {
                $file = $request->foto;
                $nom_foto = time() . '_' . $nuevo_vehiculo->id . '.' . $file->getClientOriginalExtension();
                $nuevo_vehiculo->foto = $nom_foto;
                $file->move(public_path() . '/imgs/vehiculos/', $nom_foto);
            }
            $nuevo_vehiculo->save();

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_vehiculo, "vehiculos");
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
            return redirect()->route("vehiculos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Vehiculo $vehiculo)
    {
        return response()->JSON($vehiculo);
    }

    public function update(Vehiculo $vehiculo, Request $request)
    {
        if ($request->hasFile('foto')) {
            $this->validacion['foto'] = 'image|mimes:jpeg,jpg,png|max:4096';
        }

        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($vehiculo, "vehiculos");
            $vehiculo->update(array_map('mb_strtoupper', $request->except("foto")));

            if ($request->hasFile('foto')) {
                $antiguo = $vehiculo->foto;
                if ($antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/vehiculos/' . $antiguo);
                }
                $file = $request->foto;
                $nom_foto = time() . '_' . $vehiculo->id . '.' . $file->getClientOriginalExtension();
                $vehiculo->foto = $nom_foto;
                $file->move(public_path() . '/imgs/vehiculos/', $nom_foto);
            }
            $vehiculo->save();

            $datos_nuevo = HistorialAccion::getDetalleRegistro($vehiculo, "vehiculos");
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
            return redirect()->route("vehiculos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Vehiculo $vehiculo)
    {
        DB::beginTransaction();
        try {
            $usos = Programacion::where("vehiculo_id", $vehiculo->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($vehiculo, "vehiculos");
            $vehiculo->delete();
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
