<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fence;
class FenceController extends Controller
{
    //
    public function getFence(Request $request){
        $this->validate($request,[
            'userId'=>'required'
        ]);
        $fence =Fence::where('userId',$request->userId)->get();
        return $fence;
    }

    public function storeFence(Request $request){
        $this->validate($request,[
            'fence'=>'required',
            'userId'=>'required'
        ]);
        $fences = json_encode($request->fence);
        $userId = $request->userId;
        return Fence::create([
            'fence'=>$fences,
            'userId'=>$userId
        ]);

    }
}
