<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    //

    public function createUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'required',
            'password' => 'required|min:6',
            'userType' => 'required'
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'password' => $password,
            'userType' => $request->userType
        ]);
        
        return response()->json([
            'user' => $user
        ], 201);

    }

    public function readUser(){
        return User::all();
    }

    public function updateUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'required',
            'password' => 'required|min:6',
            'userType' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'userType' => $request->userType
        ];
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        
        $user = User::where('id',$request->id)->update($data);

        return response()->json([
            'user'=>$user
        ],200);
    }

    public function deleteUser(){

    }
}
