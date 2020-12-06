<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fence;
class FenceController extends Controller
{
    //
    public function getFence(Request $request){
        $this->validate($request,[
            'imei'=>'required'
        ]);
        $fence =Fence::where('imei',$request->imei)->orderBy('created_at','desc')->get();
        return $fence;
    }

    public function storeFence(Request $request){
        $this->validate($request,[
            'fenceName'=>'required',
            'fenceType'=>'required',
            'location'=>'required',
            'imei'=>'required'
        ]);
        
        return Fence::create([
            'fenceName'=>$request->fenceName,
            'fenceType'=>$request->fenceType,
            'location'=>json_encode($request->location),
            'imei'=>$request->imei
        ]);
    }

    public function updateFence(Request $request){
        $this->validate($request,[
            'fenceId'=>'required',
            'fenceName'=>'required',
            'fenceType'=>'required'
        ]);
        // $fence = json_encode($request->fence);
        return Fence::where('id',$request->fenceId)->update([
            'fenceType'=>$request->fenceType,
            'fenceName'=>$request->fenceName,
        ]);
    }

    public function removeFence(Request $request){
        $this->validate($request,[
            'fenceId'=>'required'
        ]);
        return Fence::where('id',$request->fenceId)->delete();
    }
}
