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
            $table->unsignedBigInteger("empresa_id");
            $table->double("p_adjudicacion", 8, 2);
            $table->double("cantidad");
            $table->integer("cantidad_entero");
            $table->timestamps();

            $table->foreign("empresa_id")->on("empresas")->references("id");
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
