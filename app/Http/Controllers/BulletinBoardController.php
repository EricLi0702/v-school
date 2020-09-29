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
        return BulletinBoard::create([
            'userId'=>$userId,
            'addData'=>$content,
            'contentType'=>$contentType
        ]);
    }

    public function getQuestionnaire(Request $request){
        return BulletinBoard::orderBy('created_at','desc')->with('user')->with('content')->get();
    }
}
