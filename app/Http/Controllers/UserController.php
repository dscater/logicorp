<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\VentaLote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static $permisos = [
        "ADMINISTRADOR" => [
            "usuarios.index",
            "usuarios.create",
            "usuarios.edit",
            "usuarios.destroy",

            "proveedors.index",
            "proveedors.create",
            "proveedors.edit",
            "proveedors.destroy",

            "empresas.index",
            "empresas.create",
            "empresas.edit",
            "empresas.destroy",

            "conductors.index",
            "conductors.create",
            "conductors.edit",
            "conductors.destroy",

            "vehiculos.index",
            "vehiculos.create",
            "vehiculos.edit",
            "vehiculos.destroy",

            "productos.index",
            "productos.create",
            "productos.edit",
            "productos.destroy",

            "contratos.index",
            "contratos.create",
            "contratos.edit",
            "contratos.destroy",

            "asignacions.index",
            "asignacions.create",
            "asignacions.edit",
            "asignacions.destroy",

            "programacions.index",
            "programacions.create",
            "programacions.edit",
            "programacions.destroy",

            "viajes.index",
            "viajes.create",
            "viajes.edit",
            "viajes.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.usuarios",
            "reportes.consolidacion_viajes",
            "reportes.consolidacion_viajes_empresas",
            "reportes.consolidacion_viajes_facturacion",
            "reportes.pagos_empresas",
            "reportes.predicciones",
        ],
        "GERENTE" => [
            "proveedors.index",
            "proveedors.create",
            "proveedors.edit",
            "proveedors.destroy",

            "empresas.index",
            "empresas.create",
            "empresas.edit",
            "empresas.destroy",

            "conductors.index",
            "conductors.create",
            "conductors.edit",
            "conductors.destroy",

            "vehiculos.index",
            "vehiculos.create",
            "vehiculos.edit",
            "vehiculos.destroy",

            "productos.index",
            "productos.create",
            "productos.edit",
            "productos.destroy",

            "contratos.index",
            "contratos.create",
            "contratos.edit",
            "contratos.destroy",

            "asignacions.index",
            "asignacions.create",
            "asignacions.edit",
            "asignacions.destroy",

            "programacions.index",
            "programacions.create",
            "programacions.edit",
            "programacions.destroy",

            "viajes.index",
            "viajes.create",
            "viajes.edit",
            "viajes.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.consolidacion_viajes",
            "reportes.consolidacion_viajes_empresas",
            "reportes.consolidacion_viajes_facturacion",
            "reportes.pagos_empresas",
            "reportes.predicciones",
        ],
        "OPERADOR" => [
            "vehiculos.index",
            "vehiculos.create",
            "vehiculos.edit",
            "vehiculos.destroy",

            "contratos.index",
            "contratos.create",
            "contratos.edit",
            "contratos.destroy",

            "asignacions.index",
            "asignacions.create",
            "asignacions.edit",
            "asignacions.destroy",

            "programacions.index",
            "programacions.create",
            "programacions.edit",
            "programacions.destroy",

            "viajes.index",
            "viajes.create",
            "viajes.edit",
            "viajes.destroy",

            "configuracions.index",
            "configuracions.create",
            "configuracions.edit",
            "configuracions.destroy",

            "reportes.consolidacion_viajes",
            "reportes.consolidacion_viajes_empresas",
            "reportes.consolidacion_viajes_facturacion",
            "reportes.pagos_empresas",
        ],
    ];

    public static function getPermisosUser()
    {
        $array_permisos = self::$permisos;
        if ($array_permisos[Auth::user()->tipo]) {
            return $array_permisos[Auth::user()->tipo];
        }
        return [];
    }


    public static function verificaPermiso($permiso)
    {
        if (in_array($permiso, self::$permisos[Auth::user()->tipo])) {
            return true;
        }
        return false;
    }

    public function permisos(Request $request)
    {
        return response()->JSON([
            "permisos" => $this->permisos[Auth::user()->tipo]
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }

    public static function getInfoBoxUser()
    {
        $tipo = Auth::user()->tipo;
        $array_infos = [];

        if (in_array('usuarios.index', self::$permisos[$tipo])) {
            $array_infos[] = [
                'label' => 'USUARIOS',
                'cantidad' => User::where('id', '!=', 1)->where("tipo", "!=", "CLIENTE")->count(),
                'color' => 'bg-principal',
                'icon' => "fa-users",
                "url" => "usuarios.index"
            ];
        }

        if (in_array('contratos.index', self::$permisos[$tipo])) {
            $contratos = Contrato::count();
            $array_infos[] = [
                'label' => 'CONTRATOS',
                'cantidad' => $contratos,
                'color' => 'bg-principal',
                'icon' => "fa-clipboard-list",
                "url" => "contratos.index"
            ];
        }

        if (in_array('productos.index', self::$permisos[$tipo])) {
            $productos = Producto::count();
            $array_infos[] = [
                'label' => 'PRODUCTOS',
                'cantidad' => $productos,
                'color' => 'bg-principal',
                'icon' => "fa-box",
                "url" => "productos.index"
            ];
        }

        if (in_array('vehiculos.index', self::$permisos[$tipo])) {
            $vehiculos = Vehiculo::count();
            $array_infos[] = [
                'label' => 'VEHÍCULOS',
                'cantidad' => $vehiculos,
                'color' => 'bg-principal',
                'icon' => "fa-truck",
                "url" => "vehiculos.index"
            ];
        }

        return $array_infos;
    }
}
