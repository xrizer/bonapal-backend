<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $pending = Invoice::where('status','pending')->where('customer_id',auth()->guard('api_customer')->user()->id)->count();
        $succes = Invoice::where('status','succes')->where('customer_id',auth()->guard('api_customer')->user()->id)->count();
        $expired = Invoice::where('status','expired')->where('customer_id',auth()->guard('api_customer')->user()->id)->count();
        $failed = Invoice::where('status','failed')->where('customer_id',auth()->guard('api_customer')->user()->id)->count();

        return response()->json([
            'succes' => true,
            'message' => 'Statistik Data',
            'data' => [
                'count' => [
                    'pending' => $pending,
                    'succes' => $succes,
                    'expired' => $expired,
                    'failed' => $failed
                ]
            ]
        ],200);
    }
}
