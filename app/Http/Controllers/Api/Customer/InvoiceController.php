<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index(){
        $invoices = Invoice::latest()->when(request()->q,function ($invoices){
            $invoices = $invoices->where('invoice','like','%'.request()->q .'%');
        })->where('customer_id',auth()->guard('api_customer')->user()->id)->paginate(5);
        return new InvoiceResource(true,'List Data Invoices: '.auth()->guard('api_customer')->user()->name.'',$invoices);
    }

    public function show($snap_token){
        $invoice =  Invoice::with('orders.product','customer','city','province')->where('customer_id',auth()->guard('api_customer')->user()->id)->where('snap_token',$snap_token)->first();
        if($invoice){
            return new InvoiceResource(true,'Detail data invoice :'.$invoice->snap_token.'',$invoice);
        }
        return new InvoiceResource(false,'Detail data invoice tidak ditemukan',null);
    }
}
