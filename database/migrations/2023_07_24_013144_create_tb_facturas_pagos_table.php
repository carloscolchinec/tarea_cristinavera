<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFacturasPagosTable extends Migration
{
    public function up()
    {
        Schema::create('tb_facturas_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->date('fecha_pago');
            $table->decimal('monto_pago', 10, 2);
            $table->boolean('pagado')->default(false); 
            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('tb_facturas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_facturas_pagos');
    }
}
