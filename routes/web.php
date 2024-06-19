<?php

use App\Http\Controllers\admin\ConfigController;
use App\Http\Controllers\admin\MenssageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ChekoutController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliverymenController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\RouteController;
use App\Models\ChatBot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use App\Notifications\NewOrderNotification;

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

// routes/web.php

Route::get('/admin/notifications', 'NotificationController@index')->name('admin.notifications.index');
Route::get('/admin/notifications/mark-as-read/{id}',  'NotificationController@markAsRead')->name('admin.notifications.markAsRead');
Route::get('/admin/notifications/check', [NotificacaoController::class, 'check'])->name('admin.notifications.check');



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
    Route::get('/1', 'index2');
});

Route::prefix('/checkout')->controller(ChekoutController::class)->group(function () {
    Route::get('/pedido/{phone}', 'index');
    Route::get('/', 'index')->name('checkout.home');
    Route::get('/adicionar-produto/{id}', 'addProduto');
    Route::get('/adicionar-2-sabores', 'add2Sabores');
    Route::get('/cart/remove/{id}', 'removeCartItem');
    Route::get('/cart', 'showCart')->name('cart.show');
    Route::post('/enviaImagen', 'enviaImagen')->name('checkout.enviaImagen');
    Route::get('/finalizar', 'finish');
    Route::get('/iniciaratendimento', 'iniciar');
    Route::post('/addToCart', 'addToCart')->name('cart.add');
    Route::post('/addToCart2', 'addToCart2')->name('cart.add2');
    Route::post('/update-taxa-entrega', 'updateTaxaEntrega')->name('update-taxa-entrega');
    Route::get('/cart/update-quantity/{index}/{quantity}', 'updateCartItemQuantity')->name('cart.update-quantity');
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


        Route::prefix('/clientes')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'index')->name('admin.customer.index');
            Route::get('/novo', 'create')->name('admin.customer.create');
            Route::post('/excluir', 'destroy')->name('admin.customer.delete');
            Route::get('/editar/{id}', 'edit')->name('admin.customer.edit');
            Route::post('/store', 'store')->name('admin.customer.store');
            Route::put('/update/{customer}', 'update')->name('admin.customer.update');
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
            Route::get('/apagaNotifica', 'index2')->name('admin.order.index2');
            Route::get('/getOrders', 'getOrders');
            Route::post('/atualizar-status', 'updateStatus');
            Route::get('/getOrder', 'getOrder');
        });

        Route::prefix('/config')->controller(ConfigController::class)->group(function () {
            Route::get('/', 'index')->name('admin.config.index');
            Route::put('/', 'update')->name('admin.config.update');
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

        Route::prefix('/produtos')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('admin.product.index');
            Route::post('/store', 'store')->name('admin.product.store');
            Route::post('/storeSistem', 'storeSistem')->name('admin.product.storeSistem');
            Route::get('/novo', 'create')->name('admin.product.create');
            Route::delete('/destroy/{product}', 'destroy')->name('admin.product.destroy');
            Route::put('/destroy/{product}', 'update')->name('admin.product.update');
            Route::get('/edita', 'edit')->name('admin.product.edit');
        });
    });
});


Route::get('/teste', function () {
    $filePath = base_path('/customers.csv'); // Atualize o caminho para o seu arquivo CSV

    // dd($filePath);
    if (file_exists($filePath)) {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Define a primeira linha como cabeçalho

        $records = $csv->getRecords();
        foreach ($records as $record) {
            Customer::create([
                'name' => $record['NOME'],
                'jid' => $record['FONE1'],
                'public_place' => $record['ENDERECO'],
                'number' => $record['NUMERO'],
                'neighborhood' => $record['BAIRRO'],
                'zipcode' => '', // Adicione um valor padrão ou ajuste conforme necessário
                'city' => '', // Adicione um valor padrão ou ajuste conforme necessário
                'state' => '', // Adicione um valor padrão ou ajuste conforme necessário
                'complement' => $record['REFERENCIA'] // Adicione este campo no seu modelo e migração se necessário
            ]);
        }
    }
});
Route::get('/testePrint', function () {
    // Obter o carrinho da sessão
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Seu carrinho está vazio.');
    }
    // Recuperar o customer da sessão
    $customer = session()->get('customer');


    // Criar o pedido
    $order = Order::create([
        'customer_id' => $customer->id,
        'status_id' => 1,
        'total_price' => array_sum(array_column($cart, 'total')) + session('taxa_entrega')
    ]);

    // Criar os itens do pedido
    foreach ($cart as $item) {
        // Dividir os product_ids em primário e secundário e terciário
        $productIds = explode(',', $item['product_id']);
        $primaryProductId = $productIds[0];
        $secondaryProductId = isset($productIds[1]) ? $productIds[1] : null;
        $tertiaryProductId = isset($productIds[2]) ? $productIds[2] : null;

        OrderItem::create([
            'order_id' => $order->id,
            'product_id_primary' => $primaryProductId,
            'product_id_secondary' => $secondaryProductId,
            'product_id_tertiary' => $tertiaryProductId,
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'crust' => $item['crust'],
            'crust_price' => $item['crust_price'],
            'observation_primary' => isset($item['observation']) && $item['observation'] !== '' ? $item['observation'] : null,
            'observation_secondary' => isset($item['observation_secondary']) && $item['observation_secondary'] !== '' ? $item['observation_secondary'] : null,
            'observation_tertiary' => isset($item['observation_tertiary']) && $item['observation_tertiary'] !== '' ? $item['observation_tertiary'] : null,

        ]);
    }
   return view('teste', compact('cart'));
});



Route::get('/testeendereco', function () {
    $address1 = '4 rua antigo continente, parque bologne, SP';
    $address2 = '52 estrada da cumbica, cidade ipava, SP';

    $coords1 = getCoordinates($address1);
    $coords2 = getCoordinates($address2);

    if ($coords1 && $coords2) {
        list($distance, $duration) = getDistance($coords1, $coords2);
        return response()->json([
            'distance' => $distance,
            'duration' => $duration,
        ]);
    } else {
        return response()->json(['error' => 'Failed to retrieve coordinates for one or both addresses.'], 400);
    }
   
});
function getCoordinates($address)
{
    $url = "https://maps.googleapis.com/maps/api/geocode/json";
    $response = Http::get($url, [
        'address' => $address,
        'key' => 'AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw',
    ]);

    $data = $response->json();

    if (!empty($data['results'])) {
        $location = $data['results'][0]['geometry']['location'];
        return [$location['lat'], $location['lng']];
    }

    return null;
}


function getDistance($originCoords, $destinationCoords)
{
    $origins = implode(',', $originCoords);
    $destinations = implode(',', $destinationCoords);

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
    $response = Http::get($url, [
        'origins' => $origins,
        'destinations' => $destinations,
        'key' => 'AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw',
    ]);

    $data = $response->json();

    if (!empty($data['rows'][0]['elements'][0]['distance']) && !empty($data['rows'][0]['elements'][0]['duration'])) {
        $distance = $data['rows'][0]['elements'][0]['distance']['text'];
        $duration = $data['rows'][0]['elements'][0]['duration']['text'];
        return [$distance, $duration];
    }

    return [null, null];
}
