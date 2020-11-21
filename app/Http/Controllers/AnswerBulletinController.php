<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnswerBulletin;
use App\BulletinBoard;
class AnswerBulletinController extends Controller
{
    //
    public function addAnswerBulletin(Request $request){
        $answerData = json_encode($request->answerData);
        $userId = $request->userId;
        $bulletinId = $request->bulletinId;
        $answerUserList = BulletinBoard::select('answerUserList')->where('id',$bulletinId)->first();
        
        $answerUserList = $answerUserList->answerUserList;
        if($answerUserList == null){
            $answerUserList = $userId;
        }else{
            $answerUserList = $answerUserList.",".$userId;
        }
        BulletinBoard::where('id',$bulletinId)->update(['answerUserList'=>$answerUserList]);
        AnswerBulletin::create([
            'userId'=>$userId,
            'answerData'=>$answerData,
            'bulletinId'=>$bulletinId
        ]);
        return BulletinBoard::where('id',$bulletinId)->with(['user','content','answers','comments','likes'])->get();
    }

    public function getAnswerBulletin(Request $request){
        $bulletinId = $request->bulletinId;

        return AnswerBulletin::select('answerData')->where(['bulletinId'=>$bulletinId])->get();
    }

    public function getAnswerData(Request $request){
        $bulletinId = $request->bulletinId;
        return AnswerBulletin::where('bulletinId',$bulletinId)->with('user')->get();
    }
    public function getMyAnswerData(Request $request){
        $userId = $request->userId;
        $bulletinId = $request->bulletinId;
        return AnswerBulletin::where([['userId','=',$userId],['bulletinId','=',$bulletinId]])->with('user')->get();
    }
}
