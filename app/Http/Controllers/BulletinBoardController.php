<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'contentType'=>$contentType,
            'foamingPosition'=>$request->foamingPosition
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
        $schoolId = $request->schoolId;
        $bulletinList = BulletinBoard::where('foamingPosition',$schoolId)->orderBy('fixed_top', 'desc')->orderBy('created_at','desc')->with(['user','content','answers.user','comments.user','likes'])->paginate(5);
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
        $bulletinList = BulletinBoard::where([['contentType','=',$contentType]])->orderBy('fixed_top', 'desc')->orderBy('created_at','desc')->with(['user','content','answers.user','comments.user','likes'])->get();
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

    public function getHomework(Request $request){
        return BulletinBoard::where('contentType',20)->get();
    }

    public function addView(Request $request){
        $userId = Auth::user()->id;
        $bulletinId = $request->id;
        $bulletinBoardData = BulletinBoard::where('id', $bulletinId)->first();
        $currentViewCnt = $bulletinBoardData->view_cnt;
        if($currentViewCnt == null){
            $currentViewCnt[] = $userId;
            $bulletinBoardData->view_cnt = $currentViewCnt;
            $bulletinBoardData->save();
            return $currentViewCnt;
        }
        else{
            if (in_array($userId, $currentViewCnt)) {
                return $currentViewCnt;
            }
            else{
                array_push($currentViewCnt, $userId);
                $bulletinBoardData->view_cnt = $currentViewCnt;
                $bulletinBoardData->save();
                return $currentViewCnt;
            }
        }
    }
}
