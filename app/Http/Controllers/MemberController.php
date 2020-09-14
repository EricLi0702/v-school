<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use DB;
class MemberController extends Controller
{
    //
    public function getGrade(){
        // $grade = Member::select('grade',SUM('teacherCnt'),SUM('studentCnt'))->groupBy('grade')->get();
        $grade_info = DB::table('members')
                        ->select('grade',DB::raw('count(class) AS classCnt'),DB::raw('SUM(teacherCnt) AS teacherCnt'),DB::raw('SUM(studentCnt) AS studentCnt'))
                        ->groupBy('grade')
                        ->orderBy('id')
                        ->get();
        return $grade_info;
    }

    public function getGradeClass(Request $request){
        $grade = $request->grade;
        return Member::where('grade',$request->grade)->get();
    }
}
