<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category')
            ->withAvg('reviews','rating')
            ->withCount('reviews')
            ->when(request()->q,function ($products){
                $products = $products->where('title','like','%'.request()->q.'%');
            })->latest()->paginate(8);
        return new ProductResource(true,'List Data Products',$products);
    }

    public function show($slug){
        $product = Product::with('category','reviews.customer')
            ->withAvg('reviews','rating')
            ->withCount('reviews')
            ->where('slug',$slug)->first();
        if ($product){
            return new ProductResource(true,'Detail Data Product!',$product);
        }
        return new ProductResource(false,'Detail Data Product tidak ditemukan!',null);
    }
}
