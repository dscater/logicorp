<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Pago;
use App\Models\Programacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PagoController extends Controller
{
    public $validacion = [
        "programacion_id" => "required",
        "viaje_id" => "required",
        "mes_anio" => "required",
        "cto" => "required",
        "fecha" => "required|date",
        "retencion" => "required",
        "total_pagado" => "required",
    ];

    public $mensajes = [
        "programacion_id.required" => "Este campo es obligatorio",
        "viaje_id.required" => "Este campo es obligatorio",
        "mes_anio.required" => "Este campo es obligatorio",
        "cto.required" => "Este campo es obligatorio",
        "fecha.required" => "Este campo es obligatorio",
        "fecha.date" => "Debes ingresar una fecha valida",
        "retencion.required" => "Este campo es obligatorio",
        "total_pagado.required" => "Este campo es obligatorio",
    ];

    public function index(Programacion $programacion)
    {
        return Inertia::render("Pagos/Index", compact("programacion"));
    }

    public function listado()
    {
        $pagos = Pago::select("pagos.*")->get();
        return response()->JSON([
            "pagos" => $pagos
        ]);
    }

    public function api(Programacion $programacion, Request $request)
    {
        $pagos = Pago::with(["programacion"])->select("pagos.*");
        $pagos->where("programacion_id", $programacion->id);
        $pagos = $pagos->get();
        return response()->JSON(["data" => $pagos]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $pagos = Pago::select("pagos.*");

        if (trim($search) != "") {
            $pagos->where("nombre", "LIKE", "%$search%");
        }

        $pagos = $pagos->paginate($request->itemsPerPage);
        return response()->JSON([
            "pagos" => $pagos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear el pago
            $request["fecha_registro"] = date("Y-m-d");
            $datos = [
                "programacion_id" => $request->programacion_id,
                "viaje_id" => $request->viaje_id,
                "mes_anio" => $request->mes_anio,
                "cto" => $request->cto,
                "fecha" => $request->fecha,
                "retencion" => $request->retencion,
                "desc_merma" => $request->desc_merma ? $request->desc_merma : null,
                "total_pagado" => $request->total_pagado,
                "fecha_registro" => date("Y-m-d")
            ];
            $nuevo_pago = Pago::create($datos);
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_pago, "pagos");

            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN PAGO',
                'datos_original' => $datos_original,
                'modulo' => 'PAGOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("pagos.index", $nuevo_pago->programacion_id)->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Pago $pago)
    {
        return response()->JSON($pago->load(["programacion"]));
    }

    public function update(Pago $pago, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos = [
                "programacion_id" => $request->programacion_id,
                "viaje_id" => $request->viaje_id,
                "mes_anio" => $request->mes_anio,
                "cto" => $request->cto,
                "fecha" => $request->fecha,
                "retencion" => $request->retencion,
                "desc_merma" => $request->desc_merma ? $request->desc_merma : null,
                "total_pagado" => $request->total_pagado,
            ];

            $datos_original = HistorialAccion::getDetalleRegistro($pago, "pagos");
            $pago->update(array_map('mb_strtoupper', $datos));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($pago, "pagos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN PAGO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PAGOS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("pagos.index", $pago->programacion_id)->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Pago $pago)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($pago, "pagos");
            $pago->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN PAGO',
                'datos_original' => $datos_original,
                'modulo' => 'PAGOS',
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
