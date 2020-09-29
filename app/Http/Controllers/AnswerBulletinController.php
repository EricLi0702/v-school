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
        return AnswerBulletin::create([
            'userId'=>$userId,
            'answerData'=>$answerData,
            'bulletinId'=>$bulletinId
        ]);
    }

    public function getAnswerBulletin(Request $request){
        $bulletinId = $request->bulletinId;
        // $userId = $request->userId;
        return AnswerBulletin::select('answerData')->where(['bulletinId'=>$bulletinId])->get();
        // $answerData = AnswerBulletin::select('answerData')->where(['bulletinId'=>$bulletinId])->get();
        // foreach($answerData as $everyAnswer){
        //     $everyAnswer = json_decode($everyAnswer->answerData);
        //     file_put_contents('everyAnswer.txt',$everyAnswer);
        //     $singleAnswerData = $everyAnswer->content->singleContentDataArr;
        //     $multiAnswerData = $everyAnswer->content->multiContentDataArr;
        //     $questionAnswerData = $everyAnswer->content->questionAnswerDataArr;
        //     $statisticsAnswerData = $everyAnswer->content->statisticsDataArr;
        //     $scoringAnswerData = $everyAnswer->content->scoringQuestoinsDataArr;
        // }
    }
}
