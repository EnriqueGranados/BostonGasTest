<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsSalesTable extends Migration
{
    public function up()
    {
        Schema::create('details_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_identifier'); // Relación con la tabla ventas
            $table->string('nombre'); // Nombre del producto
            $table->integer('stock'); // Cantidad de productos
            $table->decimal('price', 8, 2); // Precio del producto
            $table->decimal('total', 8, 2); // Total de la línea
            $table->timestamps();

            // Relación de clave foránea con la tabla ventas
            $table->foreign('account_identifier')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('details_sales');
    }
}
