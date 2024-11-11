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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string("marca");
            $table->string("modelo");
            $table->string("anio");
            $table->string("placa");
            $table->string("nro_chasis");
            $table->string("color");
            $table->string("foto")->nullable();
            $table->string("descripcion", 600)->nullable();
            $table->string("nro_bin")->nullable();
            $table->string("nro_cha_tanque")->nullable();
            $table->string("marca_tanque")->nullable();
            $table->string("capacidad_tanque")->nullable();
            $table->string("nro_comportamiento")->nullable();
            $table->string("volumen_tanque")->nullable();
            $table->string("ejes_tanque")->nullable();
            $table->string("nro_precientos")->nullable();
            $table->string("tipo_tanque")->nullable();
            $table->unsignedBigInteger("conductor_id");
            $table->string("observacion", 600)->nullable();
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("conductor_id")->on("conductors")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
