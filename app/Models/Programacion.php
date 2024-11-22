<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "contrato_id",
        "empresa_id",
        "asociacion_id",
        "producto_id",
        "proveedor_id",
        "vehiculo_id",
        "conductor_id",
        "origen_destino",
        "frontera",
        "fecha_programacion",
        "descripcion",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "fecha_programacion_t", "mes_anio", "full_name"];

    public function getMesAnioAttribute()
    {
        $meses = [
            "01" => "ENERO",
            "02" => "FEBRERO",
            "03" => "MARZO",
            "04" => "ABRIL",
            "05" => "MAYO",
            "06" => "JUNIO",
            "07" => "JULIO",
            "08" => "AGOSTO",
            "09" => "SEPTIEMBRE",
            "10" => "OCTUBRE",
            "11" => "NOVIEMBRE",
            "12" => "DICIEMBRE",
        ];

        $mes = date("m", strtotime($this->fecha_programacion));
        $anio = date("Y", strtotime($this->fecha_programacion));

        return $meses[$mes] . ' ' . $anio;
    }

    public function getFullNameAttribute()
    {
        return $this->contrato->codigo . '|' . $this->empresa->razon_social . '|' . $this->asociacion->razon_social . '|' . $this->producto->nombre;
    }

    public function getFechaProgramacionTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_programacion));
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function asociacion()
    {
        return $this->belongsTo(Empresa::class, 'asociacion_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'conductor_id');
    }
}
