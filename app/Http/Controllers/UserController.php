<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    //

    public function index(Request $request){
        if(!Auth::check()){
            if($request->path() != 'login'){
                return redirect('/login');
            }else{
                return view('welcome');
            }
        }  
        $user = Auth::user();
        if($user && $request->path() == 'login'){
            return redirect('/');
        }
        return $this->checkForPermission($user,$request);
        // return view('welcome');
    }

    public function checkForPermission($user, $request){
        $permission = json_decode($user->role->permission);

        $hasPermission = false;
        if(!$permission){
            return view('welcome');
        }
        foreach($permission as $p){
            if($p->name == $request->path()){
                if($p->read){
                    $hasPermission = true;
                }
            }
        }
        if($hasPermission) return view('welcome');
        return view('notfound');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function createUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6',
            'roleId' => 'required'
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'password' => $password,
            'roleId'=>$request->roleId
            // 'userType' => $request->userType
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
            'phoneNumber' => "required|unique:users,phoneNumber,$request->id",
            'password' => 'min:6',
            'roleId' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'roleId' => $request->roleId
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
            // return Auth::check();
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
