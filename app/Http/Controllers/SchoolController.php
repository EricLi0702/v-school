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
        ]);
        return School::create([
            'schoolName'=>$request->schoolName,
        ]);
    }

    public function updateSchool(Request $request){
        $this->validate($request,[
            'schoolName'=>'required',
            'id'=>'required'
        ]);
        return School::where('id',$request->id)->update([
            'schoolName'=>$request->schoolName,
        ]);

    }

    public function removeSchool(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return School::where('id',$request->id)->delete();
    }
}
