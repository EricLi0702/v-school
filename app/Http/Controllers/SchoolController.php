<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
class SchoolController extends Controller
{
    //
    public function getSchool(Request $request){
        return School::all();
    }
    public function storeSchool(Request $request){
        $this->validate($request,[
            'schoolName'=>'required',
            'imgUrl'=>'required',
        ]);
        return School::create([
            'schoolName'=>$request->schoolName,
            'imgUrl'=>$request->imgUrl,
        ]);
    }

    public function updateSchool(Request $request){
        $this->validate($request,[
            'schoolName'=>'required',
            'imgUrl'=>'required',
            'id'=>'required'
        ]);
        return School::where('id',$request->id)->update([
            'schoolName'=>$request->schoolName,
            'imgUrl'=>$request->imgUrl
        ]);

    }

    public function removeSchool(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return School::where('id',$request->id)->delete();
    }

    public function getSchoolLessonList(Request $request){
        return School::with('lessons')->get();
    }
    public function getSchoolTree(Request $request){
        return School::with('grades.lessons')->get();
    }
}
