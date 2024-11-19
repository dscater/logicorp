<?php

use App\Http\Controllers\ConductorController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
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
    Route::get("proveedors/info/{urbanizacion}", [ProveedorController::class, 'info'])->name("proveedors.info");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // EMPRESAS
    Route::get("empresas/api", [EmpresaController::class, 'api'])->name("empresas.api");
    Route::get("empresas/paginado", [EmpresaController::class, 'paginado'])->name("empresas.paginado");
    Route::get("empresas/listado", [EmpresaController::class, 'listado'])->name("empresas.listado");
    Route::get("empresas/info/{urbanizacion}", [EmpresaController::class, 'info'])->name("empresas.info");
    Route::resource("empresas", EmpresaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CONDUCTORS
    Route::get("conductors/api", [ConductorController::class, 'api'])->name("conductors.api");
    Route::get("conductors/paginado", [ConductorController::class, 'paginado'])->name("conductors.paginado");
    Route::get("conductors/listado", [ConductorController::class, 'listado'])->name("conductors.listado");
    Route::get("conductors/info/{urbanizacion}", [ConductorController::class, 'info'])->name("conductors.info");
    Route::resource("conductors", ConductorController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // VEHICULOS
    Route::get("vehiculos/api", [VehiculoController::class, 'api'])->name("vehiculos.api");
    Route::get("vehiculos/paginado", [VehiculoController::class, 'paginado'])->name("vehiculos.paginado");
    Route::get("vehiculos/listado", [VehiculoController::class, 'listado'])->name("vehiculos.listado");
    Route::get("vehiculos/info/{urbanizacion}", [VehiculoController::class, 'info'])->name("vehiculos.info");
    Route::resource("vehiculos", VehiculoController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});
require __DIR__ . '/auth.php';
