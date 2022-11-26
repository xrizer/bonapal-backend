<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CategoryResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api_customer');
    }

    public function index(){
        $carts = Cart::with('product')
                    ->where('customer_id',auth()->guard('api_customer')->user()->id)
                    ->latest()
                    ->get();
        return new CategoryResource(true,'List Data Carts :'.auth()->guard('api_customer')->user()->name.'',$carts);
    }

    public function store(Request $request){
        $item = Cart::where('product_id',$request->product_id)->where('customer_id',auth()->guard('api_customer')->user()->id);
        if ($item->count()){
            $item->increment('qty');
            $item = $item->first();
            $price = $request->price*$item->qty;
            $weight = $request->weight *$item->qty;
            $item->update([
                'price' => $price,
                'weight'=> $weight
            ]);
        }else{
            $item = Cart::create([
                'product_id' => $request->product_id,
                'customer_id' => auth()->guard('api_customer')->user()->id,
                'qty' => $request->qty,
                'price' => $request->price,
                'weight' => $request->weight
            ]);
        }
        return new CartResource(true,'Succes Add To Cart',$item);
    }

    public function getCartPrice(){
        $totalPrice = Cart::with('product')
            ->where('customer_id',auth()->guard('api_customer')->user()->id)
            ->sum('price');
        return new CartResource(true,'Total Cart Price',$totalPrice);
    }

    public function getCartWeight(){
        $totalWeight = Cart::with('product')
            ->where('customer_id',auth()->guard('api_customer')->user()->id)
            ->sum('weight');
        return new CartResource(true,'Total Cart Weight',$totalWeight);
    }

    public function removeCart(Request $request){
        $cart = Cart::with('product')
            ->whereId($request->cart_id)
            ->first();
        $cart->delete();
        return new CartResource(true,'Succes Remove Item Cart',null);
    }
}
