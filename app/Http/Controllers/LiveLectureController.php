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
            'grade' => 'required',
            'subject' => 'required',
        ]);

        $current_date = date('Y-m-d\TH:i:sO');
        $start_date = $request->lectureTime;

        if(date('Y-m-d\TH:i:sO', strtotime($start_date)) < $current_date){
            return response()->json(['error'=>'The start time must be after the current time.'],400);
        }
        
        return LiveLecture::create([
            'userId' => Auth::user()->id,
            'teacher_name' => $request->teacherName,
            'lecture_title' => $request->lectureTitle,
            'lecture_description' => $request->lectureDescription,
            'grade' => $request->grade,
            'subject' => $request->subject,
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
        // $data = AllPost::orderBy('created_at')->paginate(10);
        // return response()->json($data);
        return LiveLecture::with(['registerlivelecture'])->orderBy('created_at','desc')->paginate(5);
    }

    public function deleteLecture(Request $request){
        $this->validate($request,[
            'lecture_title'=>'required',
            'id' => 'required'
        ]);
        return LiveLecture::where('id',$request->id)->delete();
    }

    public function updateLecture(Request $request){
        $this->validate($request,[
            'teacher_name' => 'required',
            'lecture_title' => 'required',
            'lecture_description' => 'required',
            'lecture_time' => 'required',
            'grade' => 'required',
            'subject' => 'required',
        ]);

        $current_date = date('Y-m-d\TH:i:sO');
        $start_date = $request->lecture_time;

        if(date('Y-m-d\TH:i:sO', strtotime($start_date)) < $current_date){
            return response()->json(['error'=>'The start time must be after the current time.'],400);
        }
        
        return LiveLecture::where('id',$request->id)->update([
            'teacher_name' => $request->teacher_name ,
            'lecture_title' => $request->lecture_title ,
            'lecture_description' => $request->lecture_description ,
            'lecture_time' => $request->lecture_time ,
            'grade' => $request->grade ,
            'subject' => $request->subject ,
        ]);
    }

}
