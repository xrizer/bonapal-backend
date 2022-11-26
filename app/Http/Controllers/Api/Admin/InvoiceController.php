<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        $invoices = Invoice::with('customer')->when(request()->q,function ($invoices){
                $invoices = $invoices->where('invoice','like','%'.request()->q.'%');
        })->latest()->paginate(5);

        return new InvoiceResource('true','List Data Invoices',$invoices);
    }

    public function show($id){
        $invoice = Invoice::with('orders.product','customer','city','province')->whereId($id)->first();
        if($invoice){
            return new InvoiceResource('true','detail data invoice',$invoice);
        }
        return new InvoiceResource('false','Detail data tidak ditemukan',null);
    }
}
