<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::when(request()->q,function ($customers){
            $customers = $customers->where('name','like','%'.request()->q .'%');
        })->latest()->paginate(5);
        return new CustomerResource('true','List data Customer',$customers);
    }
}
