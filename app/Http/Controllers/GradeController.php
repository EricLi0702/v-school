<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
use App\Lesson;
class GradeController extends Controller
{
    //
    public function getGrade(Request $request){
        $this->validate($request,[

        ]);
        return Grade::with('schools')->get();
    }

    public function storeGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::create([
            'gradeName'=>$request->gradeName,
            'imgUrl'=>$request->imgUrl,
            'schoolId'=>$request->schoolId
        ]);
    }

    public function updateGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::where('id',$request->id)->update([
            'gradeName'=>$request->gradeName,
            'imgUrl'=>$request->imgUrl,
            'schoolId'=>$request->schoolId
        ]);
    }

    public function removeGrade(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return Grade::where('id',$request->id)->delete();
    }

    public function gradeByClassName(Request $request){
        $classId = $request->classId;
        $school = Lesson::where('id',$classId)->get();
        $schoolId = $school[0]->schoolId;
        return Grade::where('schoolId',$schoolId)->get();
    }
}
