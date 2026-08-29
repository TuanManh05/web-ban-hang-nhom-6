<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Sắp xếp theo ID tăng dần (từ sản phẩm 1 đến 15)
        $products = Product::with('category')->orderBy('id', 'asc')->get();
        return view('products', compact('products'));
    }
}