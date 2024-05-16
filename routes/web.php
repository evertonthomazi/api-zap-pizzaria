<?php

use App\Http\Controllers\admin\MenssageController;
use App\Http\Controllers\admin\ScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliverymenController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RouteController;
use App\Models\ChatBot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/notificacoes', 'NotificacaoController@index')->name('notificacoes.index');
Route::post('/marcar-como-lida/{id}', 'NotificacaoController@marcarComoLida')->name('notificacoes.marcar_como_lida');


Route::prefix('/admin')->controller(AdminController::class)->group(function () {
    Route::get('/login', 'login')->name('admin.login');
    Route::get('/sair', 'sair')->name('admin.sair');
    Route::get('/senha', 'password')->name('admin.password');
    Route::post('/attempt', 'attempt')->name('admin.attempt');

    Route::prefix('/chat')->controller(ChatBotController::class)->group(function () {
        Route::get('/getAtendimentoPedente', 'getAtendimentoPedente');
    });
});

Route::prefix('/')->controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
});



Route::prefix('/events')->controller(EventsController::class)->group(function () {
    Route::post('/', 'index')->name('admin.events.index');
    Route::get('/teste', 'teste');
    Route::get('/mensagemEmMassa', 'mensagemEmMassa');
    Route::get('/avaliacao', 'avaliacao');
    Route::post('/avaliar', 'storeAvaliacao')->name('admin.events.avaliacao.store');
    Route::post('/avaliar', 'storeAvaliacao')->name('admin.events.avaliacao.store');
});
Route::prefix('/')->controller(AdminController::class)->group(function () {
    Route::get('/admin', 'login')->name('admin.login'); // Nomeie a rota como admin.login
});

Route::middleware(['auth.user'])->group(function () {

    Route::prefix('/formulario')->controller(FormController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/novo', 'create');
        Route::post('/store', 'store');
    });


    Route::middleware('auth.admin')->group(function () {


        Route::prefix('/agenda')->controller(ScheduleController::class)->group(function () {
            Route::get('/', 'index')->name('admin.schedule.index');
            Route::post('/atualiza', 'update')->name('admin.schedule.update');
        });



        Route::prefix('/motorista')->controller(DeliverymenController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/get-motorista-images', 'getDataMotoristaForCharts');
            Route::post('/store', 'store');
            Route::get('/detalhes/{id}', 'info');
            Route::get('/detalhesAjax/{id}', 'getInfo');
            Route::get('/lista', 'lista');
            Route::post('/update', 'update');
            Route::get('/{id}', 'show');
            Route::post('/{id}/delete', 'delete');
        });




        Route::prefix('/dashboard')->controller(DeviceController::class)->group(function () {
            Route::get('/', 'dash')->name('dashboard');
        });
        Route::prefix('/dispositivo')->controller(DeviceController::class)->group(function () {
            Route::get('/', 'index')->name('admin.device.index');
            Route::get('/novo', 'create')->name('admin.device.create');
            Route::post('/delete', 'delete')->name('admin.device.delete');
            Route::get('/getDevices', 'getDevices');
            Route::post('/updateStatus', 'updateStatus');
            Route::post('/updateName', 'updateName');
            Route::get('/getStatus', 'getStatus');
        });

        Route::prefix('/colaborador')->controller(ColaboradorController::class)->group(function () {
            Route::get('/', 'index')->name('admin.colaborador.index');
            Route::post('/novo', 'create')->name('admin.colaborador.create');
            Route::get('/edit/{id}', 'edit')->name('admin.colaborador.edit');
            Route::put('/update/{id}', 'update')->name('admin.colaborador.update');
            Route::delete('/delete/{id}', 'delete')->name('admin.colaborador.delete');
            Route::get('/lista', 'lista')->name('admin.colaborador.lista');
            Route::get('/avaliacoes/{id}', 'verAvaliacoes')->name('admin.colaborador.avaliacoes');
        });



        Route::prefix('/clientes')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'index')->name('admin.customer.index');
            Route::get('/getCustomers', 'getCustomers');
        });

        Route::prefix('/chat-bot')->controller(ChatBotController::class)->group(function () {
            Route::get('/', 'index')->name('admin.chatbot.index');
            Route::post('/store', 'store')->name('admin.menu-chat-bot.store');
        });

        Route::prefix('/atendimento')->controller(ChatBotController::class)->group(function () {
            Route::get('/', 'index')->name('admin.chat.index');
            Route::post('/up', 'up')->name('admin.chat.up');
            Route::get('/getChats', 'getChats');
        });

        Route::prefix('/pedidos')->controller(OrderController::class)->group(function () {
            Route::get('/', 'index')->name('admin.order.index');
            Route::get('/getOrders', 'getOrders');
            Route::get('/getOrder', 'getOrder');
        });

        Route::prefix('/rota')->controller(RouteController::class)->group(function () {
            Route::get('/', 'index')->name('admin.route.index');
            Route::post('/novo', 'store')->name('admin.route.store');
            Route::delete('/delete', 'delete')->name('admin.route.delete');
            Route::post('/add', 'adicionarColaborador')->name('admin.route.adicionarColaborador');
            Route::get('/edit/{id}', 'edit')->name('admin.route.edit');
        });

        Route::prefix('/mensagem')->controller(MenssageController::class)->group(function () {
            Route::get('/', 'create')->name('admin.message.create');
            Route::get('/agendamentos', 'indexAgendamentos')->name('admin.message.agendamento');
            Route::get('/getAgendamentos', 'getAgendamentos')->name('admin.message.getAgendamento');
            Route::post('/upload', 'upload')->name('upload.imagem');
            Route::post('/countContact', 'countContact');
            Route::get('/getMessage', 'getMessage');
            Route::post('/bulk', 'bulkMessage')->name('admin.message.bulk');;
            Route::get('/relatorio-de-envio', 'index')->name('admin.message.index');;
        });
    });
});


Route::get('/teste', function () {


    // Configurar o Carbon para usar o fuso horário de São Paulo
    $now = Carbon::now('America/Sao_Paulo');


    $daysOfWeek = [
        0 => 'domingo',
        1 => 'segunda',
        2 => 'terça',
        3 => 'quarta',
        4 => 'quinta',
        5 => 'sexta',
        6 => 'sábado',
    ];

    $dayOfWeek =  $daysOfWeek[$now->dayOfWeek];
    // Obter a hora e minutos atuais
    $currentTime = $now->format('H:i:s');

    // Verifique se existe um slot disponível com os parâmetros fornecidos
    $exists = DB::table('available_slots')
        ->where('day_of_week', $dayOfWeek)
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>=', $currentTime)
        ->exists();

    // Use dd() para depuração
    if ($exists) {
        dd('Slot disponível encontrado'.$currentTime);
    } else {
        dd('Nenhum slot disponível encontrado'.$currentTime);
    }



    // // Função para gerar um número aleatório de 8 dígitos
    // function generateRandomNumber() {
    //     return str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    // }

    // // Abre o arquivo CSV para escrita
    // $csvFile = fopen('telefones.csv', 'w');

    // // Escreve o cabeçalho
    // fputcsv($csvFile, array('Telefone'));

    // // Gera e escreve os números de telefone no arquivo
    // for ($i = 0; $i < 36000; $i++) {
    //     $numero = '119' . generateRandomNumber();
    //     fputcsv($csvFile, array($numero));
    // }

    // // Fecha o arquivo CSV
    // fclose($csvFile);

    // echo "Arquivo CSV gerado com sucesso!";
    // });

    // Route::get('/send', function () {

    //     // Set the endpoint URL
    //     $url = 'https://graph.facebook.com/v17.0/113092821742452/messages';

    //     // Set the access token
    //     $access_token = 'EAAbs4erX2NMBO6WjU5RPPPflR3FNC9sBUHLsPmsIdxxDKCg1LyDtCmVgZC60qoQQv4ZBtTC8yJs7MkBUIQU2lyxE4lEoiL66KCatUgVv6xBxpsVfYKHf7Yv9ZCwzpkdE4qbSwLiQZCQIZAukATDtPM2LtcwLpZCHjUF1eazY3ViMksS5pc1Vlp4hHOUAZBGHFyZBRYTvbPAUkxMnJLsw';

    //     // Set the data for the request
    //     $data = array(
    //         'messaging_product' => 'whatsapp',
    //         'to' => '554797071403',
    //         'type' => 'template',
    //         'template' => array(
    //             'name' => 'hello_world',
    //             'language' => array(
    //                 'code' => 'en_US'
    //             )
    //         )
    //     );

    //     // Convert the data to JSON format
    //     $json_data = json_encode($data);

    //     // Initialize cURL session
    //     $ch = curl_init($url);

    //     // Set cURL options
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //         'Authorization: Bearer ' . $access_token,
    //         'Content-Type: application/json'
    //     ));
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    //     // Execute cURL session and get the response
    //     $response = curl_exec($ch);

    //     // Check for cURL errors
    //     if (curl_errno($ch)) {
    //         echo 'cURL error: ' . curl_error($ch);
    //     }

    //     // Close cURL session
    //     curl_close($ch);

    //     // Print the response
    //     echo $response;
});
