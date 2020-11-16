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
        return Lesson::with('grades','schools')->get();
    }

    public function storeLesson(Request $request){
        $this->validate($request,[
            'lessonName'=>'required',
            'schoolId'=>'required',
            'imgUrl'=>'required',
            'gradeId'=>'required'
        ]);
        return Lesson::create([
            'lessonName'=>$request->lessonName,
            'schoolId'=>$request->schoolId,
            'imgUrl'=>$request->imgUrl,
            'gradeId'=>$request->gradeId
        ]);
    }

    public function updateLesson(Request $request){
        $this->validate($request,[
            'lessonName'=>'required',
            'schoolId'=>'required',
            'gradeId'=>'required',
            'imgUrl'=>'required'
        ]);
        return Lesson::where('id',$request->id)->update([
            'lessonName'=>$request->lessonName,
            'schoolId'=>$request->schoolId,
            'gradeId'=>$request->gradeId,
            'imgUrl'=>$request->imgUrl
        ]);
    }

    public function removeLesson(Request $request){
        return Lesson::where('id',$request->id)->delete();
    }

    public function getAllLesson(Request $request){
        return School::with('grades.lessons')->get();
        // return Lesson::all();
    }

    public function addClub(Request $request){
        $this->validate($request,[
            'schoolName'=>'required',
            'imgUrl'=>'required',
            'clubName'=>'required'

        ]);
        $school = School::where('schoolName',$request->schoolName)->get();
        $schoolId = $school[0]->id;
        $gradeId = Grade::create([
            'gradeName'=>'club',
            'imgUrl'=>'/img/icon/ico_group.png',
            'schoolId'=>$schoolId
        ])->id;
        
        return Lesson::create([
            'gradeId'=>$gradeId,
            'schoolId'=>$schoolId,
            'lessonName'=>$request->clubName,
            'imgUrl'=>$request->imgUrl
        ]);
    }

    public function getClub(Request $request){
        
    }
}
