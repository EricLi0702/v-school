<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Permission;
use App\Content;
use App\UserRole;
use App\Member;
use Illuminate\Support\Facades\Auth;
use Hash;
use Carbon\Carbon;
use DateTime;
class UserController extends Controller
{
    //

    public function index(Request $request){
        if(!Auth::check()){
            if($request->path() != 'login'){
                return redirect('/login');
            }else{
                return view('welcome');
            }
        }
        // return $request->path();  
        $user = Auth::user();
        $userId = $user->id;
        if($user && $request->path() == 'login'){
            return redirect('/');
        }
        $user = User::where('id',2)->with('member')->get();
        file_put_contents('test.txt',$user);
        return $this->checkForPermission($user,$request);
        // return view('welcome');
    }

    public function checkForPermission($user, $request){
        // file_put_contents('test.txt',$user);
        return view('welcome',['member'=>User::with('member.lesson.schools')->find(Auth::id())]);
        if( $user->permission == null ){
            return view('notfound');
        }
        // $member = $user->member;
        $permissions = json_decode($user->permission->permission);
        $hasPermission = false;
        if(!$permissions){
            return view('welcome');
        }
        foreach($permissions as $permission){
            foreach($permission->data as $p){
                if($p->name == $request->path()){
                    if($p->read){
                        $hasPermission = true;
                    }
                }
            }
            
        }
        if($request->path() == '/' || $request->path() == 'profile' || $request->path() == 'chat' || $request->path() == 'mobile' || $request->path() == '/mobile/postView'){
            $hasPermission = true;
        }
        if($hasPermission) return view('welcome');
        return view('notfound');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function createUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6',
            'roleId' => 'required'
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'password' => $password,
            'roleId'=>$request->roleId
            // 'userType' => $request->userType
        ]);
        
        $this->login($request);

    }

    public function addUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => 'bail|required|unique:users',
            'password' => 'bail|required|min:6',
            'roleId' => 'required'
        ]);
        $password = bcrypt($request->password);
        return User::create([
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'password' => $password,
            'roleId'=>$request->roleId
            // 'userType' => $request->userType
        ]);
    }
    public function addStaff(Request $request){
        $schoolId = Auth::user()->schoolId;
        $staffData['name'] = $request->name;
        $staffData['phoneNumber'] = $request->phoneNumber;
        $staffData['password'] = bcrypt($request->password);
        $staffData['userAvatar'] = $request->imgUrl;
        $staffData['schoolId'] = $schoolId;
        $staffData['gender'] = $request->gender;
        $staffData['nation'] = $request->nation;
        $staffData['cardNum'] = $request->cardNum;
        $staffData['roleId'] = $request->roleId;
        $staffData['isActived'] = 0;
        $staffData['familyAddress'] = json_encode($request->familyAddress);
        $staffData['residenceAddress'] = json_encode($request->residenceAddress);
        $manager = User::create($staffData);

        $memberData['schoolId'] = $schoolId;
        $memberData['gradeId'] = $request->gradeId;
        $memberData['lessonId'] = $request->lessonId;
        $memberData['userId'] = $manager->id;
        $memberData['userRoleId'] = $request->roleId;
        $member = Member::create($memberData);

        return $manager;
    }

    public function addStudent(Request $request){
        $schoolId = Auth::user()->schoolId;
        $studentData['name'] = $request->name;
        $studentData['phoneNumber'] = $request->phoneNumber;
        $studentData['password'] = bcrypt($request->password);
        $studentData['userAvatar'] = $request->imgUrl;
        $studentData['schoolId'] = $schoolId;
        $studentData['gender'] = $request->gender;
        $studentData['cardNum'] = $request->cardNum;
        $studentData['fatherName'] = $request->fatherName;
        $studentData['fatherPhone'] = $request->fatherPhone;
        $studentData['fatherJob'] = $request->fatherJob;
        $studentData['introduce'] = $request->introduce;
        $studentData['birthday'] = new DateTime($request->birthday);
        $studentData['roleId'] = 5;
        $studentData['isActived'] = 0;
        $studentData['familyAddress'] = json_encode($request->familyAddress);
        $student = User::create($studentData);

        $memberData['schoolId'] = $schoolId;
        $memberData['gradeId'] = $request->gradeId;
        $memberData['lessonId'] = $request->lessonId;
        $memberData['userId'] = $student->id;
        $memberData['userRoleId'] = 5;
        $member = Member::create($memberData);

        return $student;
    }

    public function editStudent(Request $request){
        User::where('id',$request->id)->update([
            'name'=>$request->name,
            'phoneNumber'=>$request->phoneNumber,
            'password'=>bcrypt($request->password),
            'userAvatar'=>$request->userAvatar,
            'schoolId'=>$schoolId,
            'gender'=>$request->gender,
            'cardNum'=>$request->cardNum,
            'fatherName'=>$request->fatherName,
            'fatherPhone'=>$request->fatherPhone,
            'fatherJob'=>$request->fatherJob,
            'introduce'=>$request->introduce,
            'birthday'=>new DateTime($request->birthday),
            'familyAddress'=>json_encode($request->familyAddress),
        ]);
        return response()->json([
            'msg' => 1
        ], 200);
    }

    public function getstudentBylessonId(Request $request){
        $lessonId = $request->lessonId;
        $gradeId = $request->gradeId;
        $studentList = [];
        $memberList = Member::where([['lessonId', '=', $lessonId]])->where([['gradeId', '=', $gradeId]])->where([['userRoleId', '=', 5]])->get();
        foreach ($memberList as $key => $member) {
            $userId = $member->userId;
            $userData = User::where([['id', '=', $userId]])->where([['roleId', '=', 5]])->first();
            array_push($studentList, $userData); 
        }
        return $studentList;
    }

    public function readUser(){
        return User::all();
    }
    public function readstudent(){
        return User::where([['roleId', '=', '5']])->where([['schoolId', '=', Auth::user()->schoolId]])->get();
    }

    public function updateUser(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'phoneNumber' => "required|unique:users,phoneNumber,$request->id",
            'password' => 'min:6',
            'roleId' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'phoneNumber' => $request->phoneNumber,
            'roleId' => $request->roleId
        ];
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        
        $user = User::where('id',$request->id)->update($data);
        Permission::where('userId',$request->id)->update([
            'roleId'=>$request->roleId
        ]);
        return response()->json([
            'user'=>$user
        ],200);
    }

    public function deleteUser(Request $request){
        $this->validate($request,[
            'id' => 'required'
        ]);
        return User::where('id',$request->id)->delete();

    }

    public function login(Request $request){
        $this->validate($request,[
            'phoneNumber' => 'bail|required',
            'password' => 'bail|required|min:6'
        ]);
        if(Auth::attempt(['phoneNumber' =>$request->phoneNumber, 'password' => $request->password])){
            $user = Auth::user();
            if($user->isActived == 0){
                Auth::logout();
                return redirect('/login');
            }
            return response()->json([
                'msg'=> 'You are logged in',
                'user' => $user,
            ],200);
        }else{
            return response()->json([
                'msg'=> 'Incorrect login details'
            ],401);
        }
    }

    public function readContact(Request $request){
        $schoolId = $request->schoolId;
        $classId = $request->classId;
        if($classId == null){
            $schoolMembers = Member::where('schoolId', $schoolId)->get();
            $schoolMembersUserIdList = array();
            foreach ($schoolMembers as $key => $member){
                array_push($schoolMembersUserIdList, $member->userId);
            }
            $user = User::whereIn('id', $schoolMembersUserIdList)
                            ->orderBy('name','asc')
                            ->get();
            $userName = User::select('name')
                            ->whereIn('id', $schoolMembersUserIdList)
                            ->orderBy('name')
                            ->get();
            return response()->json([
                'user'=>$user,
                'userName'=>$userName
            ]);
        }

        else{
            $schoolMembers = Member::where('schoolId', $schoolId)->where('lessonId', $classId)->get();
            $schoolMembersUserIdList = array();
            foreach ($schoolMembers as $key => $member){
                array_push($schoolMembersUserIdList, $member->userId);
            }
            $user = User::whereIn('id', $schoolMembersUserIdList)
                            ->orderBy('name','asc')
                            ->get();
            $userName = User::select('name')
                            ->whereIn('id', $schoolMembersUserIdList)
                            ->orderBy('name')
                            ->get();
            return response()->json([
                'user'=>$user,
                'userName'=>$userName
            ]);
        }

    }

    public function memberContact(){
        
    }

    public function updateProfile(Request $request){
        $userId = $request->userId;
        if($request->userName){
            $userName = $request->userName;
            $data = User::where('id',$userId)->update(['name'=>$userName]);
        }else if($request->phoneNumber){
            $phoneNumber = $request->phoneNumber;
            $data = User::where('id',$userId)->update(['phoneNumber'=>$phoneNumber]);
        }else if($request->newPassword){
            $inputedOldPassword = $request->oldPassword;
            $inputedNewPassword = $request->newPassword;
            if (Hash::check($inputedOldPassword, Auth::user()->password)) {
                return response()->json([
                    'msg'=> 0,
                ]);
            }
            User::where('id',$userId)->update(['password'=>bcrypt($inputedNewPassword)]);
            return response()->json([
                'msg'=> 1,
            ]);
        }else if($request->userAvatar){
            $userAvatar = $request->userAvatar;
            $data = User::where('id',$userId)->update(['userAvatar'=>$userAvatar]);
        }else if($request->faceImg){
            $faceImg = $request->faceImg;
            $data = User::where('id',$userId)->update(['faceImg'=>$faceImg]);
        }else if($request->isActived){
            $isActived = $request->isActived;
            $data = User::where('id',$userId)->update(['isActived'=>$isActived]);
        }
        return $data;
    }

    public function updateStatus(Request $request){
        $userId = Auth::user()->id;
        $userData = User::where('id', $userId)->first();
        $userData->status = $request->status;
        $userData->statusFrom = $request->statusFrom;
        $userData->statusTo = $request->statusTo;
        $userData->save();
        return response()->json([
            'msg'=> 1
        ]);
    }

    public function newVideoCount(Request $request){
        $userId = Auth::user()->id;
        $userData = User::where('id', $userId)->first();
        $newVideoCnt = $request->new_video_cnt;
        $userData->new_video_cnt = $newVideoCnt;
        $userData->save();
    }

    public function newLiveCount(Request $request){
        $userId = Auth::user()->id;
        $userData = User::where('id', $userId)->first();
        $newLiveCnt = $request->new_live_cnt;
        $userData->new_live_cnt = $newLiveCnt;
        $userData->save();
    }

    public function getUserById(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        $id = $request->id;
        return User::where('id',$request->id)->get();
    }

    public function search(Request $request){
        $searchedUserList = array();
        $searchedContentList = array();
        $userList = User::all();
        $contentList = Content::all();
        foreach ($userList as $key => $user){
            $userName = $user->name;
            if(strpos($userName, $request->searchWord) !== false){
                // if(Auth::user()->id == $user->id){
                //     return;
                // }
                array_push($searchedUserList, $user->load('role'));
            }
        }
        foreach ($contentList as $key => $content){
            $contentName = $content->contentName;
            if(strpos($contentName, $request->searchWord) !== false){
                array_push($searchedContentList, $content);
            }
        }

        return response()->json([
            'userList'=> $searchedUserList,
            'contentList'=> $searchedContentList,
        ]);

    }
    public function getStudentList(){
        return User::where('roleId',5)->get();
    }

    public function readstaff(){
        return User::where('roleId',3)->orWhere('roleId', 4)->get();
    }

    public function getStatus(Request $request){
        $userData = User::where('id', Auth::user()->id)->first();
        if($userData->statusTo == null){
            return response()->json([
                'isChanged'=> 2, // normal status
            ]);
        }
        else{
            $statusTo = new DateTime($userData->statusTo);
            $nowTime = new DateTime();
            if($nowTime > $statusTo){
                $userData->status = 0;
                $userData->statusFrom = null;
                $userData->statusTo = null;
                $userData->save();
                return response()->json([
                    'isChanged'=> 1, // have changed to normal status
                ]);
            }
            else{
                return response()->json([
                    'isChanged'=> 0, // not changed the status
                    'status'=> $userData->status,
                    'statusFrom'=> $userData->statusFrom,
                    'statusTo'=> $userData->statusTo,
                ]);
            }
        }
    }
    
}
