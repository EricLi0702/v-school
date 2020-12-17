<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImeiManage;
class ImeiManageController extends Controller
{
    //
    public function addImeiList(Request $request){
        $imeiList = $request->imeiList;
        $userId = $request->userId;
        return ImeiManage::create([
            'userId'=>$userId,
            'imeiList'=>$imeiList
        ]);
    }
}
