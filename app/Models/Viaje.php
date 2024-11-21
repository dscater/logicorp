<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    use HasFactory;

    protected $fillable = [
        "programacion_id",
        "volumen_programado",
        "tramo",
        "nomina",
        "resolucion",
        "dim",
        "estado",
        "observaciones",
        "fecha_carga",
        "volumen_cargado",
        "total",
        "cre_carga",
        "volumen_recepcionado",
        "total2",
        "mermas",
        "dif_litros",
        "merma_ypfb",
        "merma_cobrar",
        "volumen_facturar",
        "fecha_descarga",
        "segun_cre",
        "factura_lote",
        "atq_lapaz",
        "mes_servicio",
        "dim2",
        "crt",
        "vol_crtm3",
        "peso_crt",
        "planta_carga_crt",
        "fecha_cruce_frontera",
        "mic_dta",
        "vol_mic",
        "peso_mic",
        "parte_recepcion",
        "vol_parte_mic",
        "vol_parte_lts",
        "peso_parte",
        "observaciones2",
        "nro_solicitud_hr",
        "nro_ruta",
        "fecha_hr",
        "observaciones3",
        "nro_fac_albodab",
        "fecha_factura",
        "importe_bs",
        "observaciones4",
        "observaciones_general",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function programacion()
    {
        return $this->belongsTo(Programacion::class, 'programacion_id');
    }
}
