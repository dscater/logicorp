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
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("programacion_id");
            $table->double("volumen_programado");
            $table->string("tramo");
            $table->string("nomina")->nullable();
            $table->string("resolucion")->nullable();
            $table->string("dim")->nullable();
            $table->string("estado")->nullable();
            $table->string("observaciones", 600)->nullable();
            $table->date("fecha_carga")->nullable();
            $table->double("volumen_cargado")->nullable();
            $table->double("total")->nullable();
            $table->string("cre_carga")->nullable();
            $table->double("volumen_recepcionado")->nullable();
            $table->double("total2")->nullable();
            $table->double("mermas")->nullable();
            $table->double("dif_litros")->nullable();
            $table->double("merma_ypfb")->nullable();
            $table->double("merma_cobrar")->nullable();
            $table->double("volumen_facturar")->nullable();
            $table->date("fecha_descarga")->nullable();
            $table->string("segun_cre")->nullable();
            $table->string("factura_lote")->nullable();
            $table->string("atq_lapaz")->nullable();
            $table->string("mes_servicio")->nullable();
            $table->string("dim2")->nullable();
            $table->string("crt")->nullable();
            $table->string("vol_crtm3")->nullable();
            $table->double("peso_crt")->nullable();
            $table->string("planta_carga_crt")->nullable();
            $table->date("fecha_cruce_frontera")->nullable();
            $table->string("mic_dta")->nullable();
            $table->double("vol_mic")->nullable();
            $table->double("peso_mic")->nullable();
            $table->string("parte_recepcion")->nullable();
            $table->double("vol_parte_mic")->nullable();
            $table->double("vol_parte_lts")->nullable();
            $table->double("peso_parte")->nullable();
            $table->text("observaciones2")->nullable();
            $table->string("nro_solicitud_hr")->nullable();
            $table->string("nro_ruta")->nullable();
            $table->date("fecha_hr")->nullable();
            $table->text("observaciones3")->nullable();
            $table->string("nro_fac_albodab")->nullable();
            $table->date("fecha_factura")->nullable();
            $table->decimal("importe_bs", 24, 2)->nullable();
            $table->text("observaciones4")->nullable();
            $table->text("observaciones_general")->nullable();
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("programacion_id")->on("programacions")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
