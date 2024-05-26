<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ChekoutController extends Controller
{
    public function index()
{
    $categories = Categories::with('products')->get();
    $cart = session()->get('cart', []);
    unset($cart[0]);
    session()->put('cart', array_values($cart));
    return view('front.checkout.index', compact('categories', 'cart'));
}

    public function addProduto($id)
    {
        $product = Product::findOrFail($id);
        return view('front.checkout.addProduct', compact('product'));
    }
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $crust = $request->input('crust', 'Tradicional');
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
            'observation' => $observation,
            'total' => ($product->price + ($crust === 'Tradicional' ? 0 : 5)) * $quantity,
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

        return redirect()->route('cart.show');
    }
}
