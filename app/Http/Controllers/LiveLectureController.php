<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LiveLecture;
use Illuminate\Support\Facades\Auth;

class LiveLectureController extends Controller
{
    public function registerLecture(Request $request){
        $this->validate($request,[
            'teacherName' => 'required',
            'lectureTitle' => 'required',
            'lectureDescription' => 'required',
            'lectureTime' => 'required',
            'coverImage' => 'required',
        ]);

        $current_date = date('Y-m-d\TH:i:sO');
        $start_date = $request->lectureTime;

        if(date('Y-m-d\TH:i:sO', strtotime($start_date)) < $current_date){
            return response()->json(['error'=>'error in plannedAt parameter'],422);
        }
        
        return LiveLecture::create([
            'userId' => Auth::user()->id,
            'teacher_name' => $request->teacherName,
            'lecture_title' => $request->lectureTitle,
            'lecture_description' => $request->lectureDescription,
            'cover_image' => $request->coverImage,
            'lecture_time' => $request->lectureTime,
        ]);
    }

    public function storeCoverImage(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/images/coverImageOfLiveLecture/'),$picName);
        return $picName;
    }

    public function getLecture(){
        return LiveLecture::orderBy('created_at','desc')->get();
    }

    public function deleteLecture(Request $request){
        $this->validate($request,[
            'lecture_title'=>'required',
            'id' => 'required'
        ]);
        return LiveLecture::where('id',$request->id)->delete();
    }

}
