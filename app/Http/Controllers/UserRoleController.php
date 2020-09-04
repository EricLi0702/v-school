<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserRole;
class UserRoleController extends Controller
{
    //
    public function createRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required'
        ]);

        return UserRole::create([
            'roleName'=>$request->roleName
        ]);
    }

    public function readRole(Request $request){
        return UserRole::all();
    }

    public function updateRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
            'id' => 'required'
        ]);
        return UserRole::where('id',$request->id)->update([
            'roleName'=>$request->roleName
        ]);
    }

    public function deleteRole(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return UserRole::where('id',$request->id)->delete();
    }

    public function assignRole(Request $request){
        $this->validate($request,[
            'permission' => 'required',
            'id' => 'required',
        ]);
        
        return UserRole::where('id',$request->id)->update([
            'permission' =>$request->permission
        ]);
    }
}
