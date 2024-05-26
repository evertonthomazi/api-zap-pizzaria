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
        return view('front.checkout.index', compact('categories'));
    }

    public function addProduto($id){
        $product = Product::findOrFail($id);
        return view('front.checkout.addProduct', compact('product'));
    }
}
