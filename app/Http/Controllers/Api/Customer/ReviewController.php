<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request){
        $check_review = Review::where('order_id',$request->order_id)->where('product_id',$request->product_id)->first();
        if($check_review){
            return response()->json($check_review,409);
        }
        $review = Review::create([
            'rating' => $request->rating,
            'review' => $request->review,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'customer_id' => auth()->guard('api_customer')->user()->id
        ]);

        if ($review){
            return new ReviewResource(true,'data review berhasil disimpan',$review);
        }
        return new ReviewResource(false,'data review gagal disimpan',null);
    }
}
