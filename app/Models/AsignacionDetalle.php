<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "asignacion_id",
        "contrato_detalle_id",
    ];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    public function contrato_detalle()
    {
        return $this->belongsTo(ContratoDetalle::class, 'contrato_detalle_id');
    }

    public function asignacion_empresas()
    {
        return $this->hasMany(AsignacionEmpresa::class, 'asignacion_detalle_id');
    }
}
