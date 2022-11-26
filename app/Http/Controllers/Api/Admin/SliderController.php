<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(){
        $sliders = Slider::latest()->paginate(5);
        return new SliderResource(true,'list data slider',$sliders);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $image = $request->file('image');
        $image->storeAs('public/sliders',$image->hashName());

        $slider = Slider::create([
            'image' => $image->hashName(),
            'link' => $request->link,
        ]);

        if($slider){
            return  new SliderResource(true,'data slider berhasil disimpan!',$slider);
        }

        return new SliderResource(false,'data slider gagal disimpan!',null);
    }

    public function destroy(Slider $slider){
        Storage::disk('local')->delete('public/sliders'.basename($slider->image));
        if($slider->delete()){
            return new SliderResource(true,'data slider telah terhapus',null);
        }
        return new SliderResource(false,'data slider gagal terhapus',null);
    }
}
