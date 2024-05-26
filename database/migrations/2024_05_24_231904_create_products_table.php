<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('image');
            $table->decimal('price', 8, 2);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Inserir produtos falsos
        $categories = DB::table('categories')->pluck('id');

        foreach ($categories as $category_id) {
            for ($i = 1; $i <= 10; $i++) {
                DB::table('products')->insert([
                    'name' => 'Produto ' . $i . ' da Categoria ' . $category_id,
                    'description' => 'Descrição do Produto ' . $i . ' da Categoria ' . $category_id,
                    'image' => 'https://via.placeholder.com/60',
                    'price' => rand(10, 100),
                    'category_id' => $category_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
