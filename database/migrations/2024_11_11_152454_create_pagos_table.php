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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("programacion_id");
            $table->unsignedBigInteger("viaje_id");
            $table->string("mes_anio");
            $table->string("cto");
            $table->date("fecha")->nullable();
            $table->decimal("retencion", 24, 2);
            $table->decimal("desc_merma", 24, 2)->nullable();
            $table->decimal("total_pagado", 24, 2);
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("viaje_id")->on("viajes")->references("id");
            $table->foreign("programacion_id")->on("programacions")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
