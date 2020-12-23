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
        $schoolId = $request->schoolId;
        $classId = $request->classId;
        if(is_null($classId)){
            return School::where('id',$schoolId)->with('grades.lessons')->get();
        }else{
            return Lesson::where('id',$classId)->get();
        }
        // return Lesson::all();
    }

    public function addClub(Request $request){
        $this->validate($request,[
            'schoolId'=>'required',
            'imgUrl'=>'required',
            'clubName'=>'required'

        ]);
        $grade = Grade::where('gradeName','club')->get();
        $count = count($grade);
        if($count == 0){
            $gradeId = Grade::create([
                'gradeName'=>'club',
                'imgUrl'=>'/img/icon/ico_group.png',
                'schoolId'=>$request->schoolId
            ])->id;
        }else{
            $gradeId = $grade[0]->id;
        }
        
        return Lesson::create([
            'gradeId'=>$gradeId,
            'schoolId'=>$request->schoolId,
            'lessonName'=>$request->clubName,
            'imgUrl'=>$request->imgUrl
        ]);
    }

    public function getClub(Request $request){
        
    }

    public function getOneLesson(Request $request){
        return Lesson::where('id',$request->id)->get();
    }
}
