<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Grade;
use App\Lesson;
class LessonController extends Controller
{
    //
    public function getLesson(Request $request){
        // $this->validate($request,[
            
        // ]);
        return Lesson::all();
    }

    public function storeLesson(Request $request){
        $this->validate($request,[
            'lessonName'=>'required',
            'schoolId'=>'required',
            'gradeId'=>'required'
        ]);
        return Lesson::create([
            'lessonName'=>$request->lessonName,
            'schoolId'=>$request->schoolId,
            'gradeId'=>$request->gradeId
        ]);
    }

    public function updateLesson(Request $request){
        $this->validate($request,[
            'lessonName'=>'required',
            'schoolId'=>'required',
            'gradeId'=>'required'
        ]);
        return Lesson::where('id',$request->id)->update([
            'lessonName'=>$request->lessonName,
            'schoolId'=>$request->schoolId,
            'gradeId'=>$request->gradeId
        ]);
    }

    public function removeLesson(Request $request){
        return Lesson::where('id',$request->id)->delete();
    }

    public function getAllLesson(Request $request){
        return School::where('id',1)->with('grades.lessons')->get();
    }

    
}
