<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ViajeController extends Controller
{
    public $validacion = [
        "programacion_id" => "required",
        "volumen_programado" => "required",
        "tramo" => "required",
    ];

    public $mensajes = [
        "programacion_id.required" => "Este campo es obligatorio",
        "volumen_programado.required" => "Este campo es obligatorio",
        "tramo.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Viajes/Index");
    }

    public function listado()
    {
        $viajes = Viaje::select("viajes.*")->get();
        return response()->JSON([
            "viajes" => $viajes
        ]);
    }

    public function api(Request $request)
    {
        $viajes = Viaje::with(["programacion"])->select("viajes.*");
        $viajes = $viajes->get();
        return response()->JSON(["data" => $viajes]);
    }

    public function paginado(Request $request)
    {
        $search = $request->search;
        $viajes = Viaje::select("viajes.*");

        if (trim($search) != "") {
            $viajes->where("nombre", "LIKE", "%$search%");
        }

        $viajes = $viajes->paginate($request->itemsPerPage);
        return response()->JSON([
            "viajes" => $viajes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear el viaje
            $request["fecha_registro"] = date("Y-m-d");
            $datos = [
                "programacion_id" => $request->programacion_id,
                "volumen_programado" => $request->volumen_programado,
                "tramo" => $request->tramo,
                "nomina" => $request->nomina ? $request->nomina : null,
                "resolucion" => $request->resolucion ? $request->resolucion : null,
                "dim" => $request->dim ? $request->dim : null,
                "estado" => $request->estado ? $request->estado : null,
                "observaciones" => $request->observaciones ? $request->observaciones : null,
                "fecha_carga" => $request->fecha_carga ? $request->fecha_carga : null,
                "volumen_cargado" => $request->volumen_cargado ? $request->volumen_cargado : null,
                "total" => $request->total ? $request->total : null,
                "cre_carga" => $request->cre_carga ? $request->cre_carga : null,
                "volumen_recepcionado" => $request->volumen_recepcionado ? $request->volumen_recepcionado : null,
                "total2" => $request->total2 ? $request->total2 : null,
                "mermas" => $request->mermas ? $request->mermas : null,
                "dif_litros" => $request->dif_litros ? $request->dif_litros : null,
                "merma_ypfb" => $request->merma_ypfb ? $request->merma_ypfb : null,
                "merma_cobrar" => $request->merma_cobrar ? $request->merma_cobrar : null,
                "volumen_facturar" => $request->volumen_facturar ? $request->volumen_facturar : null,
                "fecha_descarga" => $request->fecha_descarga ? $request->fecha_descarga : null,
                "segun_cre" => $request->segun_cre ? $request->segun_cre : null,
                "factura_lote" => $request->factura_lote ? $request->factura_lote : null,
                "atq_lapaz" => $request->atq_lapaz ? $request->atq_lapaz : null,
                "mes_servicio" => $request->mes_servicio ? $request->mes_servicio : null,
                "dim2" => $request->dim2 ? $request->dim2 : null,
                "crt" => $request->crt ? $request->crt : null,
                "vol_crtm3" => $request->vol_crtm3 ? $request->vol_crtm3 : null,
                "peso_crt" => $request->peso_crt ? $request->peso_crt : null,
                "planta_carga_crt" => $request->planta_carga_crt ? $request->planta_carga_crt : null,
                "fecha_cruce_frontera" => $request->fecha_cruce_frontera ? $request->fecha_cruce_frontera : null,
                "mic_dta" => $request->mic_dta ? $request->mic_dta : null,
                "vol_mic" => $request->vol_mic ? $request->vol_mic : null,
                "peso_mic" => $request->peso_mic ? $request->peso_mic : null,
                "parte_recepcion" => $request->parte_recepcion ? $request->parte_recepcion : null,
                "vol_parte_mic" => $request->vol_parte_mic ? $request->vol_parte_mic : null,
                "vol_parte_lts" => $request->vol_parte_lts ? $request->vol_parte_lts : null,
                "peso_parte" => $request->peso_parte ? $request->peso_parte : null,
                "observaciones2" => $request->observaciones2 ? $request->observaciones2 : null,
                "nro_solicitud_hr" => $request->nro_solicitud_hr ? $request->nro_solicitud_hr : null,
                "nro_ruta" => $request->nro_ruta ? $request->nro_ruta : null,
                "fecha_hr" => $request->fecha_hr ? $request->fecha_hr : null,
                "observaciones3" => $request->observaciones3 ? $request->observaciones3 : null,
                "nro_fac_albodab" => $request->nro_fac_albodab ? $request->nro_fac_albodab : null,
                "fecha_factura" => $request->fecha_factura ? $request->fecha_factura : null,
                "importe_bs" => $request->importe_bs ? $request->importe_bs : null,
                "observaciones4" => $request->observaciones4 ? $request->observaciones4 : null,
                "observaciones_general" => $request->observaciones_general ? $request->observaciones_general : null,
                "fecha_registro" => date("Y-m-d")
            ];
            $nuevo_viaje = Viaje::create($datos);
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_viaje, "viajes");

            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN VIAJE',
                'datos_original' => $datos_original,
                'modulo' => 'VIAJES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("viajes.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Viaje $viaje)
    {
        return response()->JSON($viaje->load(["programacion"]));
    }

    public function update(Viaje $viaje, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {

            $datos = [
                "programacion_id" => $request->programacion_id,
                "volumen_programado" => $request->volumen_programado,
                "tramo" => $request->tramo,
                "nomina" => $request->nomina ? $request->nomina : null,
                "resolucion" => $request->resolucion ? $request->resolucion : null,
                "dim" => $request->dim ? $request->dim : null,
                "estado" => $request->estado ? $request->estado : null,
                "observaciones" => $request->observaciones ? $request->observaciones : null,
                "fecha_carga" => $request->fecha_carga ? $request->fecha_carga : null,
                "volumen_cargado" => $request->volumen_cargado ? $request->volumen_cargado : null,
                "total" => $request->total ? $request->total : null,
                "cre_carga" => $request->cre_carga ? $request->cre_carga : null,
                "volumen_recepcionado" => $request->volumen_recepcionado ? $request->volumen_recepcionado : null,
                "total2" => $request->total2 ? $request->total2 : null,
                "mermas" => $request->mermas ? $request->mermas : null,
                "dif_litros" => $request->dif_litros ? $request->dif_litros : null,
                "merma_ypfb" => $request->merma_ypfb ? $request->merma_ypfb : null,
                "merma_cobrar" => $request->merma_cobrar ? $request->merma_cobrar : null,
                "volumen_facturar" => $request->volumen_facturar ? $request->volumen_facturar : null,
                "fecha_descarga" => $request->fecha_descarga ? $request->fecha_descarga : null,
                "segun_cre" => $request->segun_cre ? $request->segun_cre : null,
                "factura_lote" => $request->factura_lote ? $request->factura_lote : null,
                "atq_lapaz" => $request->atq_lapaz ? $request->atq_lapaz : null,
                "mes_servicio" => $request->mes_servicio ? $request->mes_servicio : null,
                "dim2" => $request->dim2 ? $request->dim2 : null,
                "crt" => $request->crt ? $request->crt : null,
                "vol_crtm3" => $request->vol_crtm3 ? $request->vol_crtm3 : null,
                "peso_crt" => $request->peso_crt ? $request->peso_crt : null,
                "planta_carga_crt" => $request->planta_carga_crt ? $request->planta_carga_crt : null,
                "fecha_cruce_frontera" => $request->fecha_cruce_frontera ? $request->fecha_cruce_frontera : null,
                "mic_dta" => $request->mic_dta ? $request->mic_dta : null,
                "vol_mic" => $request->vol_mic ? $request->vol_mic : null,
                "peso_mic" => $request->peso_mic ? $request->peso_mic : null,
                "parte_recepcion" => $request->parte_recepcion ? $request->parte_recepcion : null,
                "vol_parte_mic" => $request->vol_parte_mic ? $request->vol_parte_mic : null,
                "vol_parte_lts" => $request->vol_parte_lts ? $request->vol_parte_lts : null,
                "peso_parte" => $request->peso_parte ? $request->peso_parte : null,
                "observaciones2" => $request->observaciones2 ? $request->observaciones2 : null,
                "nro_solicitud_hr" => $request->nro_solicitud_hr ? $request->nro_solicitud_hr : null,
                "nro_ruta" => $request->nro_ruta ? $request->nro_ruta : null,
                "fecha_hr" => $request->fecha_hr ? $request->fecha_hr : null,
                "observaciones3" => $request->observaciones3 ? $request->observaciones3 : null,
                "nro_fac_albodab" => $request->nro_fac_albodab ? $request->nro_fac_albodab : null,
                "fecha_factura" => $request->fecha_factura ? $request->fecha_factura : null,
                "importe_bs" => $request->importe_bs ? $request->importe_bs : null,
                "observaciones4" => $request->observaciones4 ? $request->observaciones4 : null,
                "observaciones_general" => $request->observaciones_general ? $request->observaciones_general : null,
            ];

            $datos_original = HistorialAccion::getDetalleRegistro($viaje, "viajes");
            $viaje->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($viaje, "viajes");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN VIAJE',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'VIAJES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("viajes.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function destroy(Viaje $viaje)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($viaje, "viajes");
            $viaje->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN VIAJE',
                'datos_original' => $datos_original,
                'modulo' => 'VIAJES',
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
