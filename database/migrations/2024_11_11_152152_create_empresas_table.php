<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string("razon_social");
            $table->string("nit");
            $table->string("nom_representante");
            $table->string("ap_representante");
            $table->string("fono");
            $table->string("correo")->nullable();
            $table->string("descripcion", 600)->nullable();
            $table->string("tipo");
            $table->date("fecha_registro")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};