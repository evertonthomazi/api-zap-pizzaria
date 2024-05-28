<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Crust;
use App\Models\Product;
use Illuminate\Http\Request;

class ChekoutController extends Controller
{
    public function index()
    {
        $categories = Categories::with('products')->get();
        $cart = session()->get('cart', []);
        return view('front.checkout.index', compact('categories', 'cart'));
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
    
        // Verificar se foram selecionados dois produtos
        if (count($productIds) != 2) {
            return redirect()->back()->with('error', 'Por favor, selecione exatamente dois produtos.');
        }
    
        // Obter informações dos produtos
        $product1 = Product::findOrFail($productIds[0]);
        $product2 = Product::findOrFail($productIds[1]);
    
        // Calcular o preço total do produto considerando a quantidade
        $totalPrice = $product1->price + $product2->price;
    
        // Se houver borda selecionada, adicionar o preço da borda ao total do produto
        if ($crustId !== null) {
            $crustPrice = Crust::findOrFail($crustId)->price;
            $totalPrice += $crustPrice;
        }
    
        // Construir o item do carrinho
        $cartItem = [
            'product_id' => $productIds[0] . ',' . $productIds[1], // Combine os IDs dos produtos
            'name' => $product1->name . ' / ' . $product2->name, // Combine os nomes dos produtos
            'image' => $product1->image, // Usar imagem do primeiro produto
            'description' => $product1->description . ' / ' . $product2->description, // Combine as descrições dos produtos
            'price' => $totalPrice, // Preço total dos produtos
            'quantity' => 1, // Definindo como 1 por enquanto, pode ser ajustado conforme necessário
            'crust' => $crustId !== null ? Crust::findOrFail($crustId)->name : 'Tradicional', // Se não houver borda selecionada, usar 'Tradicional'
            'crust_price' => $crustId !== null ? $crustPrice : 0, // Se não houver borda selecionada, preço da borda será 0
            'observation' => $observation1 . ' / ' . $observation2, // Combine as observações dos produtos
            'total' => $totalPrice, // Preço total do produto
        ];
    
        // Adicionar o item ao carrinho
        $cart[] = $cartItem;
    
        // Atualizar o carrinho na sessão
        session()->put('cart', $cart);
    
        // Redirecionar para a página de checkout com uma mensagem de sucesso
        return redirect()->route('checkout.home')->with('success', 'Produto adicionado ao carrinho com sucesso.');
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
            $itemTotal = $cart[$index]['price'] * $quantity;

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
}
