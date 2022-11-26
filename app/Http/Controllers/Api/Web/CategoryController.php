<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::latest()->get();
        return new CategoryResource(true,'list all category',$categories);
    }

    public  function show($slug){
        $category = Category::with('products.category')
            ->with('products',function ($query){
                $query->withCount('reviews');
                $query->withAvg('reviews','rating');
            })
            ->where('slug',$slug)->first();
        if ($category){
            return new CategoryResource(true,'Data product by category : '.$category->name.'',$category);
        }
        return new CategoryResource(false,'Detail Data category tidak ditemukan!',null);
    }
}
