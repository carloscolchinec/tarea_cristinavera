<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFacturasProductosTable extends Migration
{
    public function up()
    {
        Schema::create('tb_facturas_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->string('nombre_producto');
            $table->decimal('precio_producto', 10, 2);
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('tb_facturas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_facturas_productos');
    }
}
