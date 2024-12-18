<?php

use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramacionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViajeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("porta.index");

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
})->name("login");

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware('auth')->prefix("admin")->group(function () {
    // INICIO
    Route::get('/inicio', [InicioController::class, 'inicio'])->name('inicio');

    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisos", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/api", [UsuarioController::class, 'api'])->name("usuarios.api");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // PROVEEDORES
    Route::get("proveedors/api", [ProveedorController::class, 'api'])->name("proveedors.api");
    Route::get("proveedors/paginado", [ProveedorController::class, 'paginado'])->name("proveedors.paginado");
    Route::get("proveedors/listado", [ProveedorController::class, 'listado'])->name("proveedors.listado");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // EMPRESAS
    Route::get("empresas/api", [EmpresaController::class, 'api'])->name("empresas.api");
    Route::get("empresas/paginado", [EmpresaController::class, 'paginado'])->name("empresas.paginado");
    Route::get("empresas/listado", [EmpresaController::class, 'listado'])->name("empresas.listado");
    Route::resource("empresas", EmpresaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CONDUCTORS
    Route::get("conductors/api", [ConductorController::class, 'api'])->name("conductors.api");
    Route::get("conductors/paginado", [ConductorController::class, 'paginado'])->name("conductors.paginado");
    Route::get("conductors/listado", [ConductorController::class, 'listado'])->name("conductors.listado");
    Route::resource("conductors", ConductorController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // VEHICULOS
    Route::get("vehiculos/api", [VehiculoController::class, 'api'])->name("vehiculos.api");
    Route::get("vehiculos/paginado", [VehiculoController::class, 'paginado'])->name("vehiculos.paginado");
    Route::get("vehiculos/listado", [VehiculoController::class, 'listado'])->name("vehiculos.listado");
    Route::resource("vehiculos", VehiculoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // PRODUCTOS
    Route::get("productos/api", [ProductoController::class, 'api'])->name("productos.api");
    Route::get("productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CONTRATOS
    Route::get("contratos/api", [ContratoController::class, 'api'])->name("contratos.api");
    Route::get("contratos/paginado", [ContratoController::class, 'paginado'])->name("contratos.paginado");
    Route::get("contratos/listado", [ContratoController::class, 'listado'])->name("contratos.listado");
    Route::resource("contratos", ContratoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // ASIGNACIONS
    Route::get("asignacions/api", [AsignacionController::class, 'api'])->name("asignacions.api");
    Route::get("asignacions/paginado", [AsignacionController::class, 'paginado'])->name("asignacions.paginado");
    Route::get("asignacions/listado", [AsignacionController::class, 'listado'])->name("asignacions.listado");
    Route::resource("asignacions", AsignacionController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // PROGRAMACIONS
    Route::get("programacions/api", [ProgramacionController::class, 'api'])->name("programacions.api");
    Route::get("programacions/paginado", [ProgramacionController::class, 'paginado'])->name("programacions.paginado");
    Route::get("programacions/listado", [ProgramacionController::class, 'listado'])->name("programacions.listado");
    Route::resource("programacions", ProgramacionController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // VIAJES
    Route::get("programacions/viajes/api/{programacion}", [ViajeController::class, 'api'])->name("viajes.api");
    Route::get("programacions/viajes/paginado", [ViajeController::class, 'paginado'])->name("viajes.paginado");
    Route::get("programacions/viajes/listado", [ViajeController::class, 'listado'])->name("viajes.listado");
    Route::get("programacions/viajes/{programacion}", [ViajeController::class, 'index'])->name("viajes.index");
    Route::post("programacions/viajes/{programacion}", [ViajeController::class, 'store'])->name("viajes.store");
    Route::resource("viajes", ViajeController::class)->only(
        ["update", "show", "destroy"]
    );

    // PAGOS
    Route::get("programacions/pagos/api/{programacion}", [PagoController::class, 'api'])->name("pagos.api");
    Route::get("programacions/pagos/paginado", [PagoController::class, 'paginado'])->name("pagos.paginado");
    Route::get("programacions/pagos/listado", [PagoController::class, 'listado'])->name("pagos.listado");
    Route::get("programacions/pagos/{programacion}", [PagoController::class, 'index'])->name("pagos.index");
    Route::post("programacions/pagos/{programacion}", [PagoController::class, 'store'])->name("pagos.store");
    Route::resource("pagos", PagoController::class)->only(
        ["update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/consolidacion_viajes', [ReporteController::class, 'consolidacion_viajes'])->name("reportes.consolidacion_viajes");
    Route::get('reportes/r_consolidacion_viajes', [ReporteController::class, 'r_consolidacion_viajes'])->name("reportes.r_consolidacion_viajes");

    Route::get('reportes/consolidacion_viajes_empresas', [ReporteController::class, 'consolidacion_viajes_empresas'])->name("reportes.consolidacion_viajes_empresas");
    Route::get('reportes/r_consolidacion_viajes_empresas', [ReporteController::class, 'r_consolidacion_viajes_empresas'])->name("reportes.r_consolidacion_viajes_empresas");

    Route::get('reportes/consolidacion_viajes_facturacion', [ReporteController::class, 'consolidacion_viajes_facturacion'])->name("reportes.consolidacion_viajes_facturacion");
    Route::get('reportes/r_consolidacion_viajes_facturacion', [ReporteController::class, 'r_consolidacion_viajes_facturacion'])->name("reportes.r_consolidacion_viajes_facturacion");

    Route::get('reportes/pagos_empresas', [ReporteController::class, 'pagos_empresas'])->name("reportes.pagos_empresas");
    Route::get('reportes/r_pagos_empresas', [ReporteController::class, 'r_pagos_empresas'])->name("reportes.r_pagos_empresas");

    Route::get('reportes/predicciones', [ReporteController::class, 'predicciones'])->name("reportes.predicciones");
    Route::get('reportes/r_predicciones', [ReporteController::class, 'r_predicciones'])->name("reportes.r_predicciones");

});
require __DIR__ . '/auth.php';
