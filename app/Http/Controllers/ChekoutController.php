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
    public function add2Sabores(){
        $categories = Categories::where('name', 'LIKE', '%Pizzas%')->get(); // Busca as categorias que contêm a palavra "Pizzas" no nome
        $products = collect(); // Cria uma coleção vazia para armazenar os produtos
    
        // Percorre todas as categorias encontradas
        foreach ($categories as $category) {
            // Adiciona os produtos da categoria atual à coleção de produtos
            $products = $products->merge($category->products);
        }
    
        $crusts = Crust::all(); // Busca todas as bordas disponíveis
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
