<?php

namespace App\Http\Controllers;

use App\Helpers\Base62Helper;
use App\Models\Categories;
use App\Models\Chat;
use App\Models\Crust;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ChekoutController extends Controller
{
    public function index($id = null)
    {
        // $cart = session()->get('cart', []);
        // unset($cart[0]);
        // session()->put('cart', array_values($cart));
        if ($id) {
            $customer = Customer::where('id', $id)->first();


            session()->put('taxa_entrega', $customer->delivery_fee);
            $chat = Chat::where(['jid' => $customer->jid, 'active' => '1'])->first();
            if ($chat) {


                session()->put('customer', $customer);
                $categories = Categories::with('products')->get();
                $cart = session()->get('cart', []);
                return view('front.checkout.index', compact('categories', 'cart', 'customer'));
            } else {
                dd('inicie um atendimento no zap');
            }
        } else {
            // Recuperar o customer da sessão
            $customer = session()->get('customer');
            if ($customer) {
                $categories = Categories::with('products')->get();
                $cart = session()->get('cart', []);
                return view('front.checkout.index', compact('categories', 'cart', 'customer'));
            } else {
                dd('inicie um atendimento no zap');
            }
        }
    }

    public function addProduto($id)
    {
        $product = Product::findOrFail($id);
        $crusts = Crust::all(); // Busca todas as bordas disponíveis

        return view('front.checkout.addProduct', compact('product', 'crusts'));
    }
    public function add2Sabores()
    {
        $categories = Categories::where('name', 'LIKE', '%Pizzas%')->get(); // Busca as categorias que contêm a palavra "Pizzas" no nome
        $crusts = Crust::all(); // Busca todas as bordas disponíveis
        $products = collect(); // Cria uma coleção vazia para armazenar os produtos

        // Percorre todas as categorias encontradas
        foreach ($categories as $category) {
            // Adiciona os produtos da categoria atual à coleção de produtos
            $products = $products->merge($category->products);
        }
        return view('front.checkout.add2Sabores', compact('products', 'crusts'));
    }
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $crust = $request->input('crust', 'Tradicional');
        $crustPrice = $request->input('crustPrice');
        $observation = $request->input('observation', '');

        $product = Product::findOrFail($productId);

        $cartItem = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $quantity,
            'crust' => $crust,
            'crust_price' => $crustPrice,
            'observation' => $observation,
            'total' => ($product->price + $crustPrice) * $quantity, // Inclui o preço da borda, se aplicável
        ];

        $cart[] = $cartItem;

        session()->put('cart', $cart);

        $cart = session()->get('cart', []);
        return redirect()->route('checkout.home')->with('success', 'Produto adicionado ao carrinho com sucesso.');
    }

    public function addToCart2(Request $request)
    {
        // Obter o carrinho da sessão
        $cart = session()->get('cart', []);



        // Obter os dados do formulário
        $productIds = json_decode($request->input('product_ids'), true); // Convertendo de string para array
        $crustId = $request->input('crust_id');
        $observation1 = $request->input('observation1');
        $observation2 = $request->input('observation2');
        $observation3 = $request->input('observation3');

        // Verificar se foram selecionados 2 ou 3 produtos
        $selectedProductCount = count($productIds);
        if ($selectedProductCount < 2 || $selectedProductCount > 3) {
            return redirect()->back()->with('error', 'Por favor, selecione entre 2 e 3 produtos.');
        }

        // Inicializar variáveis para armazenar informações dos produtos selecionados
        $productNames = [];
        $productDescriptions = [];
        $totalPrice = 0;

        // Obter informações dos produtos e calcular o preço total
        foreach ($productIds as $productId) {
            $product = Product::findOrFail($productId);
            $productNames[] = $product->name;
            $productDescriptions[] = $product->description;
        }



        // Se houver borda selecionada, adicionar o preço da borda ao total do produto
        if ($crustId !== null) {
            $crustPrice = Crust::findOrFail($crustId)->price;
            $totalPrice += $crustPrice; // Multiplicar pelo número de produtos selecionados
        }


        // Verificar se há 3 produtos e calcular o preço total usando o maior preço
        if ($selectedProductCount >= 2) {
            $productPrices = collect();
            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
                $productPrices->push($product->price);
            }
            $totalPrice += $productPrices->max();
        }

        $cartItem = [
            'product_id' => implode(',', $productIds), // Combine os IDs dos produtos
            'name' => implode(' / ', $productNames), // Combine os nomes dos produtos
            'description' => implode(' / ', $productDescriptions), // Combine as descrições dos produtos
            'price' => $totalPrice, // Preço total dos produtos
            'quantity' => 1, // Definindo como 1 por enquanto, pode ser ajustado conforme necessário
            'crust' => $crustId !== null ? Crust::findOrFail($crustId)->name : 'Tradicional', // Se não houver borda selecionada, usar 'Tradicional'
            'crust_price' => $crustId !== null ? $crustPrice : 0, // Se não houver borda selecionada, preço da borda será 0
            'observation' => $observation1 . ' / ' . $observation2 . ' / ' . $observation3, // Combine as observações dos produtos
            'total' => $totalPrice, // Preço total do produto
        ];

        // Adicionar a lógica para determinar a imagem com base no número de sabores selecionados
        if ($selectedProductCount == 2) {
            $cartItem['image'] = 'imagens/pizza_2_sabores.png';
        } elseif ($selectedProductCount == 3) {
            $cartItem['image'] = 'imagens/pizza_3_sabores.jpg';
        }

        // Adicionar o item ao carrinho
        $cart[] = $cartItem;

        // Atualizar o carrinho na sessão
        session()->put('cart', $cart);

        // Redirecionar para a página de checkout com uma mensagem de sucesso
        return redirect()->route('checkout.home')->with('success', 'Produto(s) adicionado(s) ao carrinho com sucesso.');
    }


    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('front.checkout.cart', compact('cart'));
    }

    public function removeCartItem($index)
    {
        $cart = session()->get('cart', []);
        unset($cart[$index]);
        session()->put('cart', array_values($cart));

        return back()->with('success', 'Removido com sucesso.');
    }

    public function updateCartItemQuantity($index, $quantity)
    {
        $cart = session()->get('cart', []);

        // Verificar se a quantidade é menor ou igual a 0 e, se for, remover o item do carrinho
        if ($quantity <= 0) {
            unset($cart[$index]);
        } else {
            // Atualizar a quantidade do item no carrinho
            $cart[$index]['quantity'] = $quantity;

            // Calcular o novo total do item
            $itemTotal = 0;

            // Obter os IDs dos produtos e inicializar uma array para armazenar os preços
            $productIds = explode(',', $cart[$index]['product_id']);
            $productPrices = [];

            // Iterar sobre os IDs dos produtos para obter os preços
            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
                $productPrices[] = $product->price;
            }

            // Determinar o preço do item como o maior preço entre os produtos selecionados
            $itemPrice = max($productPrices);

            // Calcular o total do item com base na nova quantidade e no preço do item
            $itemTotal = $itemPrice * $quantity;

            // Verificar se há uma borda e, se houver, adicionar ao total
            if (isset($cart[$index]['crust_price'])) {
                $itemTotal += $cart[$index]['crust_price'] * $quantity;
            }

            $cart[$index]['total'] = $itemTotal;
        }

        // Calcular o total geral do carrinho
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['total']; // Usar o novo total do item
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Alterado com sucesso.');
    }


    public function finish(Request $request)
    {
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
            'total_price' => array_sum(array_column($cart, 'total'))+session('taxa_entrega')
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

        return view('front.checkout.resumo', compact('cart'));
    }



    public function enviaImagen(Request $request)
    {
        // Recuperar o customer da sessão
        $customer = session()->get('customer');
        $service = Chat::where('jid', $customer->jid)
            ->where('active', 1)
            ->first();
        // Obtém a imagem enviada no corpo da requisição
        $imagemBase64 = $request->input('imagem');
        // Verifica se o diretório existe, se não, cria-o
        if (!Storage::disk('public')->exists('imagens')) {
            Storage::disk('public')->makeDirectory('imagens');
        }
        // Decodifica a imagem base64 e gera um nome único para o arquivo
        $imagem = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagemBase64));
        $nomeArquivo = uniqid() . '.png';

        // Salva a imagem na pasta de armazenamento (por exemplo, a pasta "public")
        $caminhoArquivo = 'imagens/' . $nomeArquivo;
        Storage::disk('public')->put($caminhoArquivo, $imagem);
        $session = Device::first();
        $this->sendImage($session->session, $customer->phone, asset('storage/' . $caminhoArquivo), '');
        echo  asset('storage/' . $caminhoArquivo);
        exit;

        date_default_timezone_set('America/Sao_Paulo');
        $horaAtual = Carbon::now();
        $horaMais45Minutos = $horaAtual->addMinutes(45);
        $text = " Pedido feito com Sucesso .";
        $this->sendMessagem($session->session, $customer->phone, $text);

        $text = "Previsão da entrega " . $horaMais45Minutos->format('H:i');
        $this->sendMessagem($session->session, $customer->phone, $text);

        $text = "Muito Obrigado! ";
        $this->sendMessagem($session->session, $customer->phone, $text);
        $service->active = 0;
        $service->update();

        session()->forget('cart');
    }

    public function sendImage($session, $phone, $nomeImagen, $detalhes)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "image" => array(
                    "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
                ),
                "caption" => $detalhes
            ),
            "delay" => 3
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        //  file_put_contents(Utils::createCode() . ".txt", $response);

        curl_close($curl);
    }

    public function sendMessagem($session, $phone, $texto)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }
    public function iniciar()
    {
        return view('front.checkout.iniciar');
    }
}
