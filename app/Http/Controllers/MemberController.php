<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use DB;
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
                        ->select('grades.id', 'grades.gradeName','grades.imgUrl',DB::raw('count(case members.userRoleId when "3" then 1 else null end) AS teacherCnt'),DB::raw('count(case members.userRoleId when "5" then 1 else null end) AS studentCnt'),DB::raw('count(members.lessonId) AS classCnt'))
                        ->leftjoin('members','grades.id','=','members.gradeId')
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
        $this->validate($request,[
            'lessonName'=>'required'
        ]);
        $lessonName = $request->lessonName;
        $lessonId = Lesson::select('id')->where('lessonName',$lessonName)->get();
        $lessonMembers = DB::table('members')
                            ->select('users.name','users.phoneNumber','users.userAvatar')
                            ->leftjoin('users','members.userId','=','users.id')
                            ->where('members.lessonId',$lessonId[0]->id)
                            ->get();
        return $lessonMembers;
    }

    public function addMember(Request $request){

    }
}
