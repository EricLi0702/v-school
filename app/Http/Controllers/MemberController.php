<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use DB;
use App\User;
use App\School;
use App\Grade;
use App\Lesson;
class MemberController extends Controller
{
    //
    public function getLesson(Request $request){
        // $grade = Member::select('grade',SUM('teacherCnt'),SUM('studentCnt'))->groupBy('grade')->get();
        $gradeId = $request->grade;
        $lessonMember = DB::table('lessons')
                        ->select('lessons.id', 'lessons.lessonName',DB::raw('count(case members.userRoleId when "3" then 1 else null end) AS teacherCnt'),DB::raw('count(case members.userRoleId when "5" then 1 else null end) AS studentCnt'))
                        ->leftjoin('members','lessons.id','=','members.lessonId')
                        ->where('lessons.gradeId',$gradeId)
                        ->groupBy('lessons.id','lessons.lessonName')
                        ->orderBy('lessons.id')
                        ->get();
        return $lessonMember;
    }

    public function getGradeClass(Request $request){
        $grade = $request->grade;
        return Member::where('grade',$request->grade)->get();
    }

    public function getGrade(Request $request){
        $gradeMember = DB::table('grades')
                        ->select('grades.id', 'grades.gradeName','grades.imgUrl',DB::raw('count(case members.userRoleId when "3" then 1 else null end) AS teacherCnt'),DB::raw('count(case members.userRoleId when "5" then 1 else null end) AS studentCnt'))
                        ->leftjoin('members','grades.id','=','members.gradeId')
                        // ->leftjoin('lessons','grades.id','=','lessons.gradeId')
                        ->groupBy('grades.id','grades.gradeName','grades.imgUrl')
                        ->orderBy('grades.gradeName')
                        ->get();
        
        return $gradeMember;
    }

    public function getSurveySchool(){
        $surveySchool = School::find(1)->grades;
        return $surveySchool;
    }

    public function getSurveyGrade(){
        $surveyGrade = Grade::with('schools')->get();
        return $surveyGrade;
    }

    public function getSurveyLesson(){
        // $surveyLesson = Lesson::with('grades')->with('schools')->get();
        // return $surveyLesson;
        $surveyLesson = DB::table('schools')
                            ->select('schools.schoolName','grades.gradeName','lessons.lessonName')
                            ->leftjoin('grades','schools.id','=','grades.schoolId')
                            ->leftjoin('lessons','grades.id','=','lessons.gradeId')
                            ->get();
        return $surveyLesson;
        
    }

    public function getLessonMember(Request $request){
        // $this->validate($request,[
        //     'lessonName'=>'required'
        // ]);
        // $lessonName = $request->lessonName;
        // $lessonId = Lesson::select('id')->where('lessonName',$lessonName)->get();
        $lessonId = $request->id;
        $lessonMembers = DB::table('members')
                            ->select('users.name','users.phoneNumber','users.userAvatar')
                            ->leftjoin('users','members.userId','=','users.id')
                            ->where('members.lessonId',$lessonId)
                            ->get();
        return $lessonMembers;
    }

    public function addMember(Request $request){
        $userInfo = $request->data;
        $lessonId = $request->lessonId;
        $nickName = $userInfo['nickName'];
        $gradeInfo = Lesson::where('id',$lessonId)->with('grades','schools')->get();
        $userId = User::create([
            'name'=>$userInfo['nickName'],
            'phoneNumber'=>$userInfo['phoneNumber'],
            'password'=>bcrypt('password'),
            'roleId'=>$userInfo['userRole']
            ])->id;
        return Member::create([
            'schoolId'=>$gradeInfo[0]->schools->id,
            'gradeId'=>$gradeInfo[0]->grades->id,
            'lessonId'=>$lessonId,
            'userId'=>$userId,
            'userRoleId'=>$userInfo['userRole']
        ]);
        // return $gradeInfo;
    }
}
