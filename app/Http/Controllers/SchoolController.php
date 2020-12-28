<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\User;
class SchoolController extends Controller
{
    //
    public function getSchool(Request $request){
        return School::all();
    }
    public function storeSchool(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required',
            'phoneNum'=>'required',
            'zipCode'=>'required',
            'head'=>'required',
            'address'=>'required',
            'introduce'=>'required',
            'imgUrl'=>'required',
        ]);
        $schoolData['schoolName'] = $request->name;
        $schoolData['code'] = $request->code;
        $schoolData['phoneNum'] = $request->phoneNum;
        $schoolData['zipCode'] = $request->zipCode;
        $schoolData['head'] = $request->head;
        $schoolData['address'] = json_encode($request->address);
        $schoolData['introduce'] = $request->introduce;
        $schoolData['imgUrl'] = $request->imgUrl;
        $school = School::create($schoolData); 
        return response()->json([
            'school' => $school
        ], 201);
    }

    public function updateSchool(Request $request){
        $this->validate($request,[
            'schoolName'=>'required',
            'code'=>'required',
            'phoneNum'=>'required',
            'zipCode'=>'required',
            'head'=>'required',
            'address'=>'required',
            'introduce'=>'required',
            'imgUrl'=>'required',
        ]);
        School::where('id',$request->id)->update([
            'schoolName'=>$request->schoolName,
            'code'=>$request->code,
            'phoneNum'=>$request->phoneNum,
            'zipCode'=>$request->zipCode,
            'head'=>$request->head,
            'address'=>json_encode($request->address),
            'introduce'=>$request->introduce,
            'imgUrl'=>$request->imgUrl
        ]);

        return response()->json([
            'msg' => 1
        ], 200);

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
    public function getSchoolName(){
        return School::select('schoolName')->get();
    }

    public function storeManager(Request $request){
        $managerData = $request->managerData;
        $schoolId = $request->schoolId;
        $managerUserData['name'] = $managerData['name'];
        $managerUserData['phoneNumber'] = $managerData['phoneNumber'];
        $managerUserData['password'] = bcrypt($managerData['password']);
        $managerUserData['userAvatar'] = $managerData['imgUrl'];
        $managerUserData['schoolId'] = $schoolId;
        $managerUserData['gender'] = $managerData['gender'];
        $managerUserData['nation'] = $managerData['nation'];
        $managerUserData['cardNum'] = $managerData['cardNum'];
        $managerUserData['roleId'] = 2;
        $managerUserData['isActived'] = 1;
        $managerUserData['familyAddress'] = json_encode($managerData['familyAddress']);
        $managerUserData['residenceAddress'] = json_encode($managerData['residenceAddress']);

        $manager = User::create($managerUserData); 
        return response()->json([
            'msg' => 1
        ], 201);
    }

    public function updateManager(Request $request){
        User::where('id',$request->id)->update([
            'name'=>$request->name,
            'phoneNumber'=>$request->phoneNumber,
            'password'=>bcrypt($request->password),
            'userAvatar'=>$request->imgUrl,
            'gender'=>$request->gender,
            'nation'=>$request->nation,
            'cardNum'=>$request->cardNum,
            'familyAddress'=>json_encode($request->familyAddress),
            'residenceAddress'=>json_encode($request->residenceAddress),
        ]);

        return response()->json([
            'msg' => 1
        ], 200);
    }

    public function getManager(Request $request){
        $schoolId = $request->id;
        $managerList = User::where([['schoolId', '=', $request->id]])->where([['roleId', '=', 2]])->get();
        return response()->json([
            'managerList' => $managerList
        ], 200);
    }

    public function removeManager(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return User::where('id',$request->id)->delete();
    }

    public function managerSchool(Request $request){
        $id = $request->id;
        return School::where('id',$id)->with('grades.lessons')->get();
    }
}
