<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionEmpresa extends Model
{
    use HasFactory;

    protected $fillable = [
        "asignacion_detalle_id",
        "empresa_id",
        "p_adjudicacion",
        "cantidad",
        "cantidad_entero",
    ];

    public function asignacion_detalle()
    {
        return $this->belongsTo(AsignacionDetalle::class, 'asignacion_detalle_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
