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
        $schoolId = $request->schoolId;
        $gradeMember = DB::table('grades')
                        ->select('grades.id', 'grades.gradeName','grades.imgUrl',DB::raw('count(case members.userRoleId when "3" then 1 else null end) AS teacherCnt'),DB::raw('count(case members.userRoleId when "5" then 1 else null end) AS studentCnt'))
                        ->leftjoin('members','grades.id','=','members.gradeId')
                        // ->leftjoin('lessons','grades.id','=','lessons.gradeId')
                        ->where('grades.schoolId',$schoolId)
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
                            ->select('users.id','users.name','users.phoneNumber','users.userAvatar')
                            ->leftjoin('users','members.userId','=','users.id')
                            ->where('members.lessonId',$lessonId)
                            ->get();
        return $lessonMembers;
    }

    public function addMember(Request $request){
        $addData = $request->data;
        $lessonId = $request->lessonId;
        $nickName = $addData['nickName'];
        $gradeInfo = Lesson::where('id',$lessonId)->with('grades','schools')->get();
        $userInfo = User::where([['name','=',$addData['nickName']],['phoneNumber','=',$addData['phoneNumber']],['roleId','=',$addData['userRole']]])->get();
        if(count($userInfo)>0){
            $userId = $userInfo[0]->id;
        }else{
            $userId = User::create([
                'name'=>$addData['nickName'],
                'phoneNumber'=>$addData['phoneNumber'],
                'password'=>bcrypt('password'),
                'roleId'=>$addData['userRole']
                ])->id;
        }
        $memberInfo = Member::where('userId',$userId)->get();
        if(count($memberInfo)>0){
            return Member::where('userId',$userId)->update([
                'schoolId'=>$gradeInfo[0]->schools->id,
                'gradeId'=>$gradeInfo[0]->grades->id,
                'lessonId'=>$lessonId,
                'userId'=>$userId,
                'userRoleId'=>$addData['userRole']
            ]);
        }else{
            return Member::create([
                'schoolId'=>$gradeInfo[0]->schools->id,
                'gradeId'=>$gradeInfo[0]->grades->id,
                'lessonId'=>$lessonId,
                'userId'=>$userId,
                'userRoleId'=>$addData['userRole']
            ]);
        }
        // return $gradeInfo;
    }

    public function removeMember(Request $request){
        $ids = $request->id;
        // if(is_array($id)){
        
        // }else{
        //     return Member::where('userId',$id[0])->delete();
        // }
        foreach($ids as $id){
            Member::where('userId',$id)->delete();
        }
        return response()->json([
            'status'=>200,
            'msg'=>"successfully deleted"
        ]);
    }

    public function getContacts(Request $request){
        $classId = $request->classId;
        // $users = Member::where('lessonId',$classId)->with('users')->get();
        $userIds = Member::select('userId')->where('lessonId',$classId)->get();
        $user = User::whereIn('id',$userIds)->orderBy('name','asc')->get();
        $userName = User::select('name')->whereIn('id',$userIds)->orderBy('name')->get();
        return response()->json([
            'user'=>$user,
            'userName'=>$userName
        ]);
        // $user = User::orderBy('name','asc')->get();
        // $userName = User::select('name')->orderBy('name')->get();
        // return response()->json([
        //     'user'=>$user,
        //     'userName'=>$userName
        // ]);
    }
}
