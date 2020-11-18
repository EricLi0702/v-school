<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HomeworkResult;
use App\BulletinBoard;
class HomeworkResultController extends Controller
{
    //
    public function addResult(Request $request){
        $this->validate($request,[
            'bulletinId'=>'required',
            'userId'=>'required',
            'postData'=>'required'
        ]);
        $answerData = json_encode($request->postData);
        $bulletinId = $request->bulletinId;
        $userId = $request->userId;
        $answerUserList = BulletinBoard::select('answerUserList')->where('id',$bulletinId)->first();
        
        $answerUserList = $answerUserList->answerUserList;
        if($answerUserList == null){
            $answerUserList = $userId;
        }else{
            $answerUserList = $answerUserList.",".$userId;
        }
        BulletinBoard::where('id',$bulletinId)->update(['answerUserList'=>$answerUserList]);
        
        return HomeworkResult::create([
            'homeworkId'=>$request->bulletinId,
            'userId'=>$request->userId,
            'answerData'=>$answerData
        ]);
    }
}
