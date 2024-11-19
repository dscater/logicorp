<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [
        "marca",
        "modelo",
        "anio",
        "placa",
        "nro_chasis",
        "color",
        "foto",
        "descripcion",
        "nro_bin",
        "nro_cha_tanque",
        "marca_tanque",
        "capacidad_tanque",
        "nro_compartamiento",
        "volumen_tanque",
        "ejes_tanque",
        "nro_precientos",
        "tipo_tanque",
        "conductor_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "full_name", "url_foto", "foto_b64"];

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/vehiculos/" . $this->foto);
        }
        return asset("imgs/vehiculos/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/vehiculos/" . $this->foto);
        if (!$this->foto || !file_exists($path)) {
            $path = public_path("imgs/vehiculos/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    public function getFullNameAttribute()
    {
        return $this->marca . '|' . $this->modelo . '|' . $this->anio . '|' . $this->placa;
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'conductor_id');
    }
}
