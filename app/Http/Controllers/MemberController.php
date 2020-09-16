<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use DB;
use App\Grade;
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
}
