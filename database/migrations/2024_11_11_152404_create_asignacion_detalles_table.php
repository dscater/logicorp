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
        Schema::create('asignacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("asignacion_id");
            $table->unsignedBigInteger("contrato_detalle_id");
            $table->timestamps();

            $table->foreign("asignacion_id")->on("asignacions")->references("id");
            $table->foreign("contrato_detalle_id")->on("contrato_detalles")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_detalles');
    }
};
