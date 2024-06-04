<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do status (ex: Pendente, Processando, Completo, Cancelado)
            $table->string('color')->nullable(); // Cor do status (opcional)
            $table->timestamps();
        });

        // Insira os status iniciais aqui
        DB::table('order_statuses')->insert([
            ['name' => 'Pendente', 'color' => '#FFA500'], // Laranja
            ['name' => 'Processando', 'color' => '#FFD700'], // Dourado
            ['name' => 'Completo', 'color' => '#32CD32'], // Verde
            ['name' => 'Cancelado', 'color' => '#FF0000'], // Vermelho
            ['name' => 'Saiu Para Entrega', 'color' => '#4169E1'], // Azul Royal
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
}
