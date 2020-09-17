<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\RegisterLiveLecture;
use App\LiveLecture;

class RegisterLiveLectureController extends Controller
{
    public function unRegisterFromLecture(Request $request){
        $lectureId = $request->id;
        $userId = Auth::user()->id;
        // $registeredMemberNum = LiveLecture::select('userId',$userId)->where('id', $lectureId)->get();
        $resultRow = LiveLecture::select('registered_members')->where('id', $lectureId)->get();
        file_put_contents("test.txt", $resultRow);
        $registeredNum = $resultRow[0]->registered_members;
        $registeredNum = $registeredNum - 1;
        LiveLecture::where('id', $lectureId)->update([
            'registered_members' => $registeredNum,
        ]);

        $result = RegisterLiveLecture::where('live_lecture_id', '=', $lectureId)->where('userId', '=', $userId)->get();
        if (isset($result)) {
            return RegisterLiveLecture::where('live_lecture_id', '=', $lectureId)->where('userId', '=', $userId)->delete();
        }
    }

    public function registerToLecture(Request $request){
        $lectureId = $request->id;
        $userId = Auth::user()->id;
        //increase number of register Num of member
        $resultRow = LiveLecture::select('registered_members')->where('id', $lectureId)->get();
        file_put_contents("test.txt", $resultRow);
        $registeredNum = $resultRow[0]->registered_members;
        $registeredNum = $registeredNum + 1;
        LiveLecture::where('id', $lectureId)->update([
            'registered_members' => $registeredNum,
        ]);
        
        //create a row about this register to RegisterLiveLecture table
        return RegisterLiveLecture::create([
            'live_lecture_id' => $lectureId,
            'userId' => $userId,
        ]);
    }
}
