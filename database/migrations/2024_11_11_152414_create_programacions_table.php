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
        Schema::create('programacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("contrato_id");
            $table->unsignedBigInteger("empresa_id");
            $table->unsignedBigInteger("asociacion_id");
            $table->unsignedBigInteger("producto_id");
            $table->unsignedBigInteger("proveedor_id");
            $table->unsignedBigInteger("vehiculo_id");
            $table->unsignedBigInteger("conductor_id");
            $table->string("origen_destino");
            $table->string("frontera");
            $table->date("fecha_programacion");
            $table->string("descripcion", 600)->nullable();
            $table->integer("reemplazo")->default(0);
            $table->unsignedBigInteger("vehiculo_remplazo_id");
            $table->text("observacion_reemplazo")->nullable();
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("contrato_id")->on("contratos")->references("id");
            $table->foreign("empresa_id")->on("empresas")->references("id");
            $table->foreign("asociacion_id")->on("empresas")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("proveedor_id")->on("proveedors")->references("id");
            $table->foreign("vehiculo_id")->on("vehiculos")->references("id");
            $table->foreign("conductor_id")->on("conductors")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programacions');
    }
};
