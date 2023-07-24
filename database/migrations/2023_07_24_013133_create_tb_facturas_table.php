<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_facturas', function (Blueprint $table) {
            $table->id();
            $table->string('cedula_cliente');
            $table->date('fecha_factura');
            $table->enum('tipo_factura', ['Contado', 'Diferido']);
            $table->unsignedInteger('cuotas')->nullable();
            $table->date('fecha_credito')->nullable();
            $table->date('fecha_final_credito')->nullable();
            $table->decimal('total_factura', 10, 2); // Puedes ajustar la precisión según tus necesidades
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_facturas');
    }
};
