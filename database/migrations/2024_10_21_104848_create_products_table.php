<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Campo id
            $table->string('name'); // Campo name
            $table->text('description'); // Campo description
            $table->decimal('price', 10, 2); // Campo price (10 dígitos en total, 2 decimales)
            $table->integer('stock'); // Campo stock
            $table->string('image')->nullable(); // Campo image (opcional)
            $table->string('category')->nullable(); // Campo category (opcional)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
