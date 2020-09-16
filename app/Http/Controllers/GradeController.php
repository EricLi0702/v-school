<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
class GradeController extends Controller
{
    //
    public function getGrade(Request $request){
        $this->validate($request,[

        ]);
        return Grade::all();
    }

    public function storeGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'gradeType'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::create([
            'gradeName'=>$request->gradeName,
            'gradeType'=>$request->gradeType,
            'imgUrl'=>$request->imgUrl,
            'schoolId'=>$request->schoolId
        ]);
    }

    public function updateGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'gradeType'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::where('id',$request->id)->update([
            'gradeName'=>$request->gradeName,
            'gradeType'=>$request->gradeType,
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
}
