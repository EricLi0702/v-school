<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImeiManage;
use Illuminate\Support\Facades\Auth;
class ImeiManageController extends Controller
{
    //
    public function addImeiList(Request $request){
        $imeiList = $request->imeiList;
        $userId = $request->userId;
        $imeiData = ImeiManage::where('userId',$userId)->get();
        if(count($imeiData)>0){
            return ImeiManage::where('userId',$userId)->update([
                'imeiList'=>$imeiList
            ]);
        }else{
            return ImeiManage::create([
                'userId'=>$userId,
                'imeiList'=>$imeiList
            ]);
        }
    }

    public function getImeiList(Request $request){
        $id = Auth::user()->id;
        $imeiList = ImeiManage::where('userId',$id)->get();
        file_put_contents('test.txt', $id);
        return ImeiManage::where('userId',$id)->get();
    }
}
