<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    //

    public function index(Request $request){
        // return Auth::user();
        // dd(Auth::check());
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }  
        return view('welcome');
    }

    public function createUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'required|unique:users',
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
            'phoneNumber' => 'required|unique:users',
            'password' => 'min:6',
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

    public function deleteUser(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        return User::where('id',$request->id)->delete();

    }

    public function login(Request $request){
        $this->validate($request,[
            'phoneNumber' => 'bail|required',
            'password' => 'bail|required|min:6'
        ]);
        if(Auth::attempt(['phoneNumber' =>$request->phoneNumber, 'password' => $request->password])){
            $user = Auth::user();
            return Auth::check();
            return response()->json([
                'msg'=> 'You are logged in',
                'user' => $user
            ],200);
        }else{
            return response()->json([
                'msg'=> 'Incorrect login details'
            ],401);
        }
    }
}
