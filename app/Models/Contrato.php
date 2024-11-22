<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo",
        "nro_cod",
        "nro_lote",
        "empresa_id",
        "p_asignado",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "mes_anio"];


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

        $mes = date("m", strtotime($this->fecha_registro));
        $anio = date("Y", strtotime($this->fecha_registro));

        return $meses[$mes] . ' ' . $anio;
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public static function getCodigoNuevoContrato()
    {
        $ultimo = Contrato::get()->last();
        $nro = 1;
        if ($ultimo) {
            $nro = (int)$ultimo->nro_cod + 1;
        }

        $codigo = "C." . $nro;
        return [$codigo, $nro];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function contrato_detalles()
    {
        return $this->hasMany(ContratoDetalle::class, 'contrato_id');
    }
}
