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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id_primary')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('product_id_secondary')->nullable()->constrained('products')->onDelete('cascade');
            $table->string('name'); // Nome do produto ou combinação de sabores
            $table->text('description')->nullable(); // Descrição do produto ou combinação de sabores
            $table->decimal('price', 10, 2); // Preço do item (considerando apenas o maior preço no caso de dois sabores)
            $table->integer('quantity');
            $table->string('crust')->default('Tradicional'); // Nome da borda
            $table->decimal('crust_price', 10, 2)->default(0.00); // Preço da borda
            $table->text('observation_primary')->nullable(); // Observação do cliente para o primeiro sabor
            $table->text('observation_secondary')->nullable(); // Observação do cliente para o segundo sabor, se houver
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
