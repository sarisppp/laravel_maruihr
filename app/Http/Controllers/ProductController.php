<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function frontend()
    {
        return Product::all();
    }

    public function backend(Request $request)
    {
        $query = Product::query();

        if ($s = $request->input('s')){
            $query->where('title','regexp',"/$s/")
                ->orWhere('description','regexp',"/$s/");
        }
        return $query->get();
    }
}
