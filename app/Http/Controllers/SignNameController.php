<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SignName;
class SignNameController extends Controller
{
    //
    public function getNameList(Request $reqeust){
        return SignName::all();
    }
    
    public function addName(Request $request){
        $addName = $request->addName;
        return SignName::create(['name'=>$request->addName]);
    }
}
