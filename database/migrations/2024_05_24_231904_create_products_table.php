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
            $table->decimal('price', 10, 2);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Adicionar produtos para a categoria "Pizzas Clássicas"
        $categoryPizzaClassicasId = DB::table('categories')->where('name', 'Pizzas Clássica')->value('id');

        $pizzasClassicas = [
            ['name' => 'Mussarela', 'description' => 'Pizza de Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Mussarela e Tomate', 'description' => 'Pizza de Mussarela e Tomate', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calabresa', 'description' => 'Pizza de Calabresa', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calabresa e Cebola', 'description' => 'Pizza de Calabresa e Cebola', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Atum', 'description' => 'Pizza de Atum', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Atum e Cebola', 'description' => 'Pizza de Atum e Cebola', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Milho', 'description' => 'Pizza de Milho', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Milho e Mussarela ou Catupiry', 'description' => 'Pizza de Milho com Mussarela ou Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Baiana 1', 'description' => 'Pizza Baiana 1 - Calabresa, Pimenta, Ovo e Cebola', 'image' => 'URL_DA_IMAGEM', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
        ];

        foreach ($pizzasClassicas as $pizza) {
            DB::table('products')->insert($pizza);
        }

        // Adicionar produtos para a categoria "Pizzas Tradicionais"
        $categoryPizzaClassicasId = DB::table('categories')->where('name', 'Pizzas Tradicionais')->value('id');

        $pizzasTradicionais = [
            ['name' => 'Alemã', 'description' => 'Frango, Bacon, Catupiry e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Americana', 'description' => 'Presunto, Bacon, Ovo e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Bacon', 'description' => 'Mussarela e Bacon', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Baiana I', 'description' => 'Mussarela, Calabresa, Cebola, Ovo e Pimenta', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Bauru', 'description' => 'Presunto, Tomate e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 36.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Brócolis I', 'description' => 'Brócolis e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Caipira I', 'description' => 'Frango, Milho e Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Chilena', 'description' => 'Frango, Bacon, Milho e Cheddar', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Escarola', 'description' => 'Escarola, Bacon e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Espanhola', 'description' => 'Presunto, Tomate, Catupiry e Parmesão', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango', 'description' => 'Frango e Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Grega', 'description' => 'Presunto, Palmito, Ervilha e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Jardinheira', 'description' => 'Frango, Cebola, Bacon e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Margarita', 'description' => 'Mussarela, Parmesão, Tomate e Manjericão', 'image' => 'URL_DA_IMAGEM', 'price' => 36.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Napolitana', 'description' => 'Mussarela, Molho e Parmesão', 'image' => 'URL_DA_IMAGEM', 'price' => 36.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Palmito', 'description' => 'Palmito e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Portuguesa', 'description' => 'Presunto, Ovo, Cebola e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Verona I', 'description' => 'Calabresa, Catupiry e Parmesão', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Dois Queijos', 'description' => 'Mussarela e Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Caruzo', 'description' => 'Frango, Bacon e Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango com Cheddar', 'description' => 'Frango e Cheddar', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Ki-Delícia', 'description' => 'Calabresa, Bacon, Cebola e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Peruana', 'description' => 'Atum, Cebola e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Caipira II', 'description' => 'Frango, Milho, Catupiry e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Brócolis II', 'description' => 'Brócolis, Bacon, Mussarela e Alho Frito', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Atumpiry', 'description' => 'Atum e Catupiry', 'image' => 'URL_DA_IMAGEM', 'price' => 37.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Três Queijos', 'description' => 'Mussarela, Catupiry e Parmesão', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Strogonoff', 'description' => 'Frango, Catupiry, Mussarela e Batata Palha', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Apollo', 'description' => 'Frango, Cheddar, Mussarela e Batata Palha', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Francesa', 'description' => 'Presunto, Ervilha, Milho e Mussarela', 'image' => 'URL_DA_IMAGEM', 'price' => 38.00, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Verona II', 'description' => 'Calabresa, Cheddar e Parmesão', 'image' => 'URL_DA_IMAGEM', 'price' => 39.00, 'category_id' => $categoryPizzaClassicasId],
        ];

        foreach ($pizzasTradicionais as $pizza) {
            DB::table('products')->insert($pizza);
        }

        // Inserir produtos para Pizzas Premium
        $premiumCategoryId = DB::table('categories')->where('name', 'Pizzas Premium')->value('id');

        $premiumPizzas = [
            ['name' => 'A Moda do Chef', 'description' => 'Lombo, frango, palmito, bacon e catupiry', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'A Moda da Casa', 'description' => 'Calabresa, frango, cebola, catupiry e parmesão', 'price' => 43.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Benjamin', 'description' => 'Lombo, tomate, milho, cheddar e mussarela', 'price' => 44.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Fornello\'s', 'description' => 'Pepperoni, cheddar, bacon, cebola e mussarela', 'price' => 45.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Batata', 'description' => 'Mussarela, batata frita, cheddar e bacon', 'price' => 43.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Carne Seca', 'description' => 'Carne seca e mussarela', 'price' => 45.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Italiana', 'description' => 'Pepperoni, bacon, palmito, cebola e mussarela', 'price' => 46.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Pepperoni', 'description' => 'Pepperoni, catupiry e mussarela', 'price' => 42.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Primavera', 'description' => 'Escarola, palmito, milho, ervilha e mussarela', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Suprema', 'description' => 'Frango, calabresa, atum, milho e mussarela', 'price' => 44.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Vegetariana', 'description' => 'Escarola, brócolis, palmito, milho, ervilha e mussarela', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Quatro Queijos', 'description' => 'Mussarela, catupiry, provolone e parmesão', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'A Moda do Pizzaiolo', 'description' => 'Peito de peru, tomate, catupiry, bacon e mussarela', 'price' => 47.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Cinco Queijos', 'description' => 'Mussarela, catupiry, provolone, parmesão e cheddar', 'price' => 45.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Carne Seca II', 'description' => 'Carne seca, cebola, ovo e mussarela', 'price' => 48.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Camarão I', 'description' => 'Camarão, cream cheese e mussarela', 'price' => 50.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Camarão II', 'description' => 'Camarão, cream cheese, cebola, tomate e mussarela', 'price' => 56.00, 'image' => 'URL_DA_IMAGEM'],
        ];

        foreach ($premiumPizzas as $pizza) {
            $pizza['category_id'] = $premiumCategoryId;
            $pizza['created_at'] = now();
            $pizza['updated_at'] = now();
            DB::table('products')->insert($pizza);
        }

         // Inserir produtos para Doces
         $docesCategoryId = DB::table('categories')->where('name', 'Pizzas Doces')->value('id');

         $doces = [
             ['name' => 'Brigadeiro', 'description' => 'Chocolate e brigadeiro', 'price' => 35.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Prestígio', 'description' => 'Chocolate e coco ralado', 'price' => 37.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Beijinho', 'description' => 'Chocolate branco e coco ralado', 'price' => 36.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Sensação', 'description' => 'Chocolate, morango, granulado, morango e granulado', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Sedução', 'description' => 'Chocolate branco, morango e raspas de chocolate preto', 'price' => 42.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'M&M', 'description' => 'Chocolate e M&M', 'price' => 43.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Maracujá', 'description' => 'Base chocolate branco e maracujá', 'price' => 39.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Dois Amores', 'description' => 'Chocolate branco e preto', 'price' => 36.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Banana', 'description' => 'Banana, leite condensado e canela', 'price' => 37.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Banana I', 'description' => 'Banana, leite condensado, mussarela e canela', 'price' => 39.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Oreo', 'description' => 'Chocolate e Oreo', 'price' => 41.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Romeu e Julieta', 'description' => 'Goiabada e mussarela', 'price' => 37.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Chocobanana', 'description' => 'Banana, chocolate preto e doce de leite', 'price' => 39.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Doce de Leite I', 'description' => 'Chocolate branco e doce de leite', 'price' => 38.00, 'image' => 'URL_DA_IMAGEM'],
         ];
 
         foreach ($doces as $doce) {
             $doce['category_id'] = $docesCategoryId;
             $doce['created_at'] = now();
             $doce['updated_at'] = now();
             DB::table('products')->insert($doce);
         }
 
         // Inserir produtos para Calzone
         $calzoneCategoryId = DB::table('categories')->where('name', 'Calzones')->value('id');
 
         $calzones = [
             ['name' => 'Frango', 'description' => 'Frango e catupiry', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Calabresa', 'description' => 'Calabresa e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Bauru', 'description' => 'Presunto, tomate e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Mussarela', 'description' => 'Mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Presunto', 'description' => 'Presunto e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Bacon', 'description' => 'Bacon e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Marguerita', 'description' => 'Mussarela, tomate e manjericão', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Palmito', 'description' => 'Palmito e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Dois Queijos', 'description' => 'Mussarela e catupiry', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Peruana', 'description' => 'Atum, cebola e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Calabresa II', 'description' => 'Calabresa, bacon, cebola e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Frango II', 'description' => 'Frango, milho e catupiry', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Americana', 'description' => 'Presunto, ovo, cebola e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
             ['name' => 'Baiana II', 'description' => 'Calabresa, bacon, cebola e mussarela', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
         ];
 
         foreach ($calzones as $calzone) {
             $calzone['category_id'] = $calzoneCategoryId;
             $calzone['created_at'] = now();
             $calzone['updated_at'] = now();
             DB::table('products')->insert($calzone);
         }
          // Inserir produtos para Calzone Doce
        $calzoneDoceCategoryId = DB::table('categories')->where('name', 'Calzone Doce')->value('id');

        $calzoneDoces = [
            ['name' => 'Brigadeiro', 'description' => 'Chocolate granulado', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Prestígio', 'description' => 'Chocolate e coco ralado', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'M&M\'s', 'description' => 'Chocolate e M&M\'s', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Dois Amores', 'description' => 'Chocolate preto e chocolate branco', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Beijinho', 'description' => 'Chocolate branco e coco ralado', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
        ];

        foreach ($calzoneDoces as $calzoneDoce) {
            $calzoneDoce['category_id'] = $calzoneDoceCategoryId;
            $calzoneDoce['created_at'] = now();
            $calzoneDoce['updated_at'] = now();
            DB::table('products')->insert($calzoneDoce);
        }

        // Inserir produtos para Porções
        $porcoesCategoryId = DB::table('categories')->where('name', 'Porções')->value('id');

        $porcoes = [
            ['name' => 'Batata Frita', 'description' => 'Batata frita', 'price' => 20.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Batata com Bacon e Cheddar', 'description' => 'Batata com bacon e cheddar', 'price' => 25.00, 'image' => 'URL_DA_IMAGEM'],
        ];

        foreach ($porcoes as $porcao) {
            $porcao['category_id'] = $porcoesCategoryId;
            $porcao['created_at'] = now();
            $porcao['updated_at'] = now();
            DB::table('products')->insert($porcao);
        }

        // Inserir produtos para Bebidas
        $bebidasCategoryId = DB::table('categories')->where('name', 'Bebidas')->value('id');

        $bebidas = [
            ['name' => 'Dolly Guaraná', 'description' => '', 'price' => 8.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Dolly Limão', 'description' => '', 'price' => 8.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Guaraná Antarctica', 'description' => '', 'price' => 12.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Coca Cola', 'description' => '', 'price' => 14.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Fanta Uva', 'description' => '', 'price' => 12.00, 'image' => 'URL_DA_IMAGEM'],
            ['name' => 'Fanta Laranja', 'description' => '', 'price' => 12.00, 'image' => 'URL_DA_IMAGEM'],
        ];

        foreach ($bebidas as $bebida) {
            $bebida['category_id'] = $bebidasCategoryId;
            $bebida['created_at'] = now();
            $bebida['updated_at'] = now();
            DB::table('products')->insert($bebida);
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
