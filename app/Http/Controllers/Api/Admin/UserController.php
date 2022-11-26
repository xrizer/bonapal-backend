<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::when(request()->q,function ($users){
            $users = $users->where('name','like','%'.request()->q.'%');
        })->latest()->paginate(5);
        return new UserResource(true,'List data Users',$users);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        if ($user){
            return new UserResource(true,'User berhasil ditambahkan',$user);
        }

        return  new UserResource(false,'user gagal ditambahkan',null);
    }

    public function show($id){
        $user = User::whereId($id)->first();
        if($user){
            return new UserResource(true,'detail data user',$user);
        }
        return  new UserResource(false,'detail tidak dapat ditampillkan',null);

    }

    public function update(Request $request,User $user){
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users,email,'.$user->id,
            'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->password == "") {

            //update user without password
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
            ]);
        }

        //update user with new password
        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        if($user) {
            //return success with Api Resource
            return new UserResource(true, 'Data User Berhasil Diupdate!', $user);
        }

        //return failed with Api Resource
        return new UserResource(false, 'Data User Gagal Diupdate!', null);
    }

    public function destroy(User $user){
        if($user->delete()){
            return new UserResource(true,'user berhasil dihapus',null);
        }
        return new UserResource(false,'user gagal dihapus',null);
    }
}
