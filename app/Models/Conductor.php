<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "nacionalidad",
        "fecha_nac",
        "sexo",
        "estado_civil",
        "nro_licencia",
        "categoria",
        "fecha_emision",
        "fecha_vencimiento",
        "fono",
        "foto",
        "observacion",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "full_name", "full_ci", "url_foto", "foto_b64", "fecha_nac_t", "fecha_emision_t", "fecha_vencimiento_t"];

    public function getFechaVencimientoTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_vencimiento));
    }

    public function getFechaEmisionTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_emision));
    }

    public function getFechaNacTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_nac));
    }

    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/users/" . $this->foto);
        }
        return asset("imgs/users/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/users/" . $this->foto);
        if (!$this->foto || !file_exists($path)) {
            $path = public_path("imgs/users/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }
}
