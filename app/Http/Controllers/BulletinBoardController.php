<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BulletinBoard;
use App\Events\NewBulletIn;
use DateTime;

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

    public function updateQuestionnaire(Request $request){
        $contentType = $request->contentType;
        $bulletinData = BulletinBoard::where('id', $request->bulletinId)->first();
        $addDataJson = json_decode($bulletinData['addData']);
        if($contentType == 24){
            $addDataJson->description->title = $request->content;
        }
        if($contentType == 4 || $contentType == 5){
            $addDataJson->content = $request->content;
        }
        if($contentType == 1 || $contentType == 2){
            $addDataJson->deadline = $request->deadline;
        }
        $bulletinData['addData'] = json_encode($addDataJson);
        $bulletinData->save();
        $bulletinData = BulletinBoard::where('id',$request->bulletinId)->with(['user','content','answers','comments','likes'])->get();
        return response()->json([
            'msg'=> 1,
            'responseData' => $bulletinData
        ]);
    }

    public function getQuestionnaire(Request $request){
        $bulletinList = BulletinBoard::orderBy('fixed_top', 'desc')->orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->paginate(5);
        foreach ($bulletinList as $key => $bulletin){
            $addData = json_decode($bulletin->addData);
            if (property_exists($addData, 'deadline')){
                $deadline = new DateTime($addData->deadline);
                $now = new DateTime();
                if($deadline < $now){
                    unset($bulletinList[$key]);
                }
            }
        }
        // foreach($bulletinBoard as $bulletinList){
        //    $viewList = json_decode ($bulletinBoard->addData->viewList);
        // }
        return $bulletinList;
    }
    
    public function getApplicationLists(Request $request){
        $contentType = $request->contentType;
        $selLesson = $request->selLesson;
        $bulletinList = BulletinBoard::where([['contentType','=',$contentType]])->orderBy('fixed_top', 'desc')->orderBy('created_at','desc')->with(['user','content','answers','comments.user','likes'])->get();
        foreach ($bulletinList as $key => $bulletin){
            $addData = json_decode($bulletin->addData);
            if (property_exists($addData, 'deadline')){
                $deadline = new DateTime($addData->deadline);
                $now = new DateTime();
                if($deadline < $now){
                    unset($bulletinList[$key]);
                }
            }
        }
        return $bulletinList;
    }

    public function deleteQuestionnaire(Request $request){
        $id = $request->id;
        return BulletinBoard::where('id',$id)->delete();
    }

    public function topQuestionnaire(Request $request){
        $bulletinBoardData = BulletinBoard::where('id', $request->id)->first();
        $bulletinBoardData['fixed_top'] = 1;
        $bulletinBoardData->save();
        return response()->json([
            'msg'=> 1
        ]);
    }

    public function uptopQuestionnaire(Request $request){
        $bulletinBoardData = BulletinBoard::where('id', $request->id)->first();
        $bulletinBoardData['fixed_top'] = 0;
        $bulletinBoardData->save();
        return response()->json([
            'msg'=> 1
        ]);
    }
}
