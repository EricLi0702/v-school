<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AllPost;
class AllPostController extends Controller
{
    //
    public function getPost(){
        $data = AllPost::orderBy('created_at')->paginate(10);
        return response()->json($data);
        // return AllPost::all();
    }

    public function isLiked (Request $request){
        $this->validate($request,[
            'id'=>'required',
            'isLiked'=>'required'
        ]);

        $query = AllPost::where('id',$request->id);
        $queryIncrement = clone $query;
        $queryDcrement = clone $query;
        $queryUpdate = clone $query; 
        $queryUpdate->update([
            'isliked'=>$request->isLiked
        ]);
        if($request->isLiked == 1){
            $queryIncrement->increment("likeCnt",1);
            return response()->json([
                'msg'=>'liked',
                'isLiked'=>true
            ]);
        }else{
            $queryDcrement->decrement('likeCnt',1);
            return response()->json([
                'msg'=>'Unliked',
                'isLiked'=>false
            ]);
        }
        return response()->json([
            'msg'=>'success'
        ]);

    }

    public function addQuestionnaire(Request $request){
        
    }
}
