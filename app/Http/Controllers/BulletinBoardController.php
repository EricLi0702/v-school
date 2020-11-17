<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BulletinBoard;
use App\Events\NewBulletIn;

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

        // broadcast Event
        broadcast(new NewBulletIn($bulletin))->toOthers();

        return response()->json($bulletin,201);
    }

    public function getQuestionnaire(Request $request){
        $bulletinList = BulletinBoard::orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->paginate(5);
        // foreach($bulletinBoard as $bulletinList){
        //    $viewList = json_decode ($bulletinBoard->addData->viewList);
        // }
        return $bulletinList;
    }
    
    public function getApplicationLists(Request $request){
        $contentType = $request->contentType;
        $selLesson = $request->selLesson;
        return BulletinBoard::where([['contentType','=',$contentType]])->orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->get();
    }

    public function deleteQuestionnaire(Request $request){
        $id = $request->id;
        return BulletinBoard::where('id',$id)->delete();
    }
}
