<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Crust;
use Faker\Factory as Faker;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crusts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2); // Altere os valores conforme a precisão necessária
            $table->timestamps();
        });

        // Crie alguns dados falsos para a tabela de bordas
        $crustsData = [
            ['name' => 'Tradicional', 'price' => 0.00],
            ['name' => 'Cheddar', 'price' => 3.00],
            ['name' => 'Chocolate', 'price' => 5.00]
        ];

        // Insira os dados na tabela de bordas
        foreach ($crustsData as $crustData) {
            Crust::create($crustData);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crusts');
    }
};
