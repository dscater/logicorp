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

    protected $appends = ["fecha_registro_t"];

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
