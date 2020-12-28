<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRole;
use App\Permission;
class UserRoleController extends Controller
{
    //
    public function createRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
            'permission'=>'required'
        ]);
        $permission = json_encode($request->permission);
        return UserRole::create([
            'roleName'=>$request->roleName,
            'permission'=>json_encode($request->permission)
        ]);
    }

    public function readRole(Request $request){
        return UserRole::all();
    }

    public function updateRole(Request $request){
        $this->validate($request,[
            'roleName' => 'required',
            'permission'=>'required',
            'id' => 'required'
        ]);
        return UserRole::where('id',$request->id)->update([
            'roleName'=>$request->roleName,
            'permission'=>json_encode($request->permission)
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
            'roleId' => 'required',
            'userId'=>'required'
        ]);
        $permission = Permission::where([['userId','=',$request->userId],['roleId','=',$request->roleId]])->get();
        if(count($permission)>0){
            return Permission::where([['userId','=',$request->userId],['roleId','=',$request->roleId]])->update([
                'permission'=>$request->permission
            ]);
        }else{
            return Permission::create([
                'permission'=>$request->permission,
                'userId'=>$request->userId,
                'roleId'=>$request->roleId
            ]);
        }
        // return Permission::where('id',$request->id)->update([
        //     'permission' =>$request->permission
        // ]);
    }

    public function getUserByRole(Request $request){
        $id = $request->id;
        return User::where('roleId',$request->id)->with(['imei'])->get();
    }
}
