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
        Schema::create('conductors', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("paterno")->nullable();
            $table->string("materno")->nullable();
            $table->string("ci");
            $table->string("ci_exp");
            $table->string("nacionalidad");
            $table->date("fecha_nac");
            $table->string("sexo");
            $table->string("estado_civil");
            $table->string("nro_licencia");
            $table->string("categoria");
            $table->date("fecha_emision");
            $table->date("fecha_vencimiento");
            $table->string("fono");
            $table->string("foto")->nullable();
            $table->date("fecha_registro")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductors');
    }
};
