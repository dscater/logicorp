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
        Schema::create('contrato_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("contrato_id");
            $table->unsignedBigInteger("proveedor_id");
            $table->unsignedBigInteger("producto_id");
            $table->string("tramo");
            $table->string("frontera");
            $table->double("cantidad");
            $table->timestamps();

            $table->foreign("contrato_id")->on("contratos")->references("id");
            $table->foreign("proveedor_id")->on("proveedors")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_detalles');
    }
};
