<?php

namespace App\Http\Controllers\Api\Admin;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    public function index(){
       $products = Product::with('category')->when(request()->q,function ($products){
           $products = $products->where('title','like','%'. request()->q . '%');
       })->latest()->paginate(5);
       return new ProductResource('true','list data products',$products);
    }

//    public  function store(Request $request){
//        $validator = Validator::make($request->all(),[
//            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
//            'title'         => 'required|unique:products',
//            'category_id'   => 'required',
//            'description'   => 'required',
//            'weight'        => 'required',
//            'price'         => 'required',
//            'stock'         => 'required',
//            'discount'      => 'required'
//        ]);
//        if($validator->fails()){
//            return response()->json($validator->errors(),422);
//        }
//
//        $image = $request->file('image');
//        $image->storeAs('public/products',$image->hashName());
//
//        $product = Product::create([
//            'image'         => $image->hashName(),
//            'title'         => $request->title,
//            'slug'          => Str::slug($request->title,'-'),
//            'category_id'   => $request->category_id,
//            'user_id'       => auth()->guard('api_user')->user()->id,
//            'description'   => $request->description,
//            'weight'        => $request->weight,
//            'price'         => $request->price,
//            'stock'         => $request->stock,
//            'discount'      => $request->discount,
//        ]);
//
//        if($product){
//            return new ProductResource('true','Data Product Berhasil Tersimpan',$product);
//        }
//        return  new ProductResource('false','Product gagal disimpan',null);
//    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title'         => 'required|unique:products',
            'category_id'   => 'required',
            'description'   => 'required',
            'weight'        => 'required',
            'price'         => 'required',
            'stock'         => 'required',
            'discount'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        $product = Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'slug'          => Str::slug($request->title, '-'),
            'category_id'   => $request->category_id,
            'user_id'       => auth()->guard('api_admin')->user()->id,
            'description'   => $request->description,
            'weight'        => $request->weight,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'discount'      => $request->discount
        ]);

        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Disimpan!', $product);
        }

        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $product = Product::whereId($id)->first();
        if($product){
            return new ProductResource('true','Detail data product',$product);
        }
        return  new ProductResource('false','Detail data product tidak ditemukan !',null);
    }

    public function update(Request $request,Product $product){
        $validator = Validator::make($request->all(),[
            'title' => 'required|unique:products,title,'.$product->id,
            'category_id' => 'required',
            'description' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'discount' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        if($request->file('image')){
            Storage::disk('local')->delete('public/products'.basename($product->image));
            $image = $request->file('image');
            $image ->storeAs('public/products',$image->hashName());
            // update product with image
            $product->update([
               'image' => $image->hashName(),
               'title' =>$request->title,
               'slug' => Str::slug($request->title,'-'),
               'category_id' => $request->category_id,
               'user_id' => auth()->guard('api_admin')->user()->id,
               'description' => $request->description,
               'weight' => $request->weight,
               'price' => $request->price,
               'stock' => $request->stock,
               'discount' => $request->discount,
            ]);
        }
        $product->update([
            'title' =>$request->title,
            'slug' => Str::slug($request->title,'-'),
            'category_id' => $request->category_id,
            'user_id' => auth()->guard('api_admin')->user()->id,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount' => $request->discount,
        ]);
        if($product){
            return  new ProductResource('true','Data product berhasil diupdate',$product);
        }
        return  new ProductResource('false','Data product gagal diupdate',null);
    }

    public function destroy(Product $product){
        Storage::disk('local')->delete('public/products'.basename($product->image));
        if($product->delete()){
            return new ProductResource(true,'Data berhasil dihapus',null);
        }
        return new ProductResource(false,'data gagal dihapus',null);
    }
}
