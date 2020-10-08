<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BulletinBoard;
class BulletinBoardController extends Controller
{
    //
    public function addQuestionnaire(Request $request){
        $data = $request->data;
        $content = json_encode($data);
        $userId = $request->userId;
        $contentType = $request->contentType;
        $id = BulletinBoard::create([
            'userId'=>$userId,
            'addData'=>$content,
            'contentType'=>$contentType
        ])->id;
        $bulletin = BulletinBoard::where('id',$id)->with(['user','content','answers','comments','likes'])->get();
        return response()->json($bulletin,201);
    }

    public function getQuestionnaire(Request $request){
        return BulletinBoard::orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->get();
    }
    
    public function getApplicationLists(Request $request){
        $contentType = $request->contentType;
        return BulletinBoard::where('contentType',$contentType)->orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->get();
    }
}
