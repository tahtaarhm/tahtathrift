<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class CustomerController extends Controller
{
    public function index()
    {
        $categories=Category::all();
        $products=Product::with('category')->limit(3)->get();
        
        return view('customer',compact("categories","products"));
    }
}

