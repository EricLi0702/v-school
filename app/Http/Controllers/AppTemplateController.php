<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppTemplate;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Exports\questionnaireExport;
use App\User;
use App\Member;
use Maatwebsite\Excel\Facades\Excel;
class AppTemplateController extends Controller
{
    //
    public function imageUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/image'),$picName);
        return $picName;
    }

    public function otherUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:doc,docx,zip,pdf,xls,xlsx,rp,mp3,rp,ppt,pptx,pptm,apk,rar'
        ]);
        $file = $request->file();
        $fileSize = $request->file->getSize();
        if($fileSize>1024*1024){
            $fileSize = round($fileSize/1024/1024,1).'MB';
        }else{
            $fileSize = round($fileSize/1024,1).'KB';
        }
        $fileOriName = $request->file->getClientOriginalName();
        $fileExtension = $request->file->extension();
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/other'),$fileName);
        return response()->json([
            'fileName'=>$fileName,
            'fileOriName'=>$fileOriName,
            'fileSize'=>$fileSize,
            'fileExtension'=>$fileExtension    
        ]);
    }

    public function videoUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:mp4'
        ]);
        $file = $request->file();
        $fileSize = $request->file->getSize();
        if($fileSize>1024*1024){
            $fileSize = round($fileSize/1024/1024,1).'MB';
        }else{
            $fileSize = round($fileSize/1024,1).'KB';
        }
        $fileOriName = $request->file->getClientOriginalName();
        $fileExtension = $request->file->extension();
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/video'),$fileName);
        return response()->json([
            'fileName'=>$fileName,
            'fileOriName'=>$fileOriName,
            'fileSize'=>$fileSize,
            'fileExtension'=>$fileExtension    
        ]);
    }

    public function deleteFile(Request $request){
        $fileName = $request->fileName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }

    public function deleteFileFromServer($fileName){
        $filePath = public_path().$fileName;
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }


    public function getTemplate(Request $request){
        $contentType = $request->contentType;
        return AppTemplate::where('contentType',$contentType)->get();
    }

    public function storeTemplate(Request $request){
        // $this->validate($request,[

        // ]);
        $templateName = $request->templateName;
        if($request->templateCover){
            $templateCover = $request->templateCover;
        }else{
            $templateCover = $request->imgUrl;
        }
        $templateTitle = $request->title;
        $templateDesc = $request->description;
        $templateContent = json_encode($request->content);
        $contentType = $request->contentType;
        $templateType = $request->templateType;
        return AppTemplate::create([
            'templateName'=>$templateName,
            'imgUrl'=>$templateCover,
            'title'=>$templateTitle,
            'description'=>$templateDesc,
            'content'=>$templateContent,
            'contentType'=>$contentType,
            'templateType'=>$templateType
        ]);

    }

    public function excelUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:doc,docx,xls,xlsx'
        ]);
        $file = $request->file();
    }

    public function userExport() 
    {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
    }
    public function userImport(Request $request) 
    {
        $this->validate($request,[
            'file' => 'required|mimes:doc,docx,xls,xlsx'
        ]);
        // Excel::import(new UsersImport, $request->file);
        $array = Excel::toArray(new UsersImport, $request->file);
        foreach($array as $key => $users){
            foreach($users as $key=>$user){
                $status = User::where('phoneNumber',$user['phonenumber'])->first();
                if(isset($status->id)){
                    return response()->json([
                        "status"=>400,
                        "msg"=>"用户已经存在",
                        "phoneNumber"=>$user['phonenumber']
                    ]);
                }
            }
        }
        foreach($array as $key => $users){
            foreach($users as $key=>$user){
                User::create([
                    'name'=>$user['name'],
                    'phoneNumber'=>$user['phonenumber'],
                    'password'=>bcrypt('password'),
                    'roleId'=>1
                ]);
            }
        }
        return response()->json([
            'status'=>200,
            'msg'=>'成功上传'
        ]);
        // return;
    }
    public function memberImport(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:doc,docx,xls,xlsx'
        ]);
        $array = Excel::toArray(new UsersImport, $request->file);
        foreach($array as $key => $users){
            foreach($users as $key=>$user){
                $status = User::where('phoneNumber',$user['phonenumber'])->first();
                if(isset($status->id)){
                    return response()->json([
                        "status"=>400,
                        "msg"=>"用户已经存在",
                        "phoneNumber"=>$user['phonenumber']
                    ]);
                }
            }
        }
        foreach($array as $key=>$users){
            foreach($users as $key=>$user){
                $userId = User::create([
                    'name'=>$user['name'],
                    'phoneNumber'=>$user['phonenumber'],
                    'password'=>bcrypt('password'),
                    'roleId'=>$user['role']
                ])->id;
                Member::create([
                    'schoolId'=>$user['school'],
                    'gradeId'=>$user['grade'],
                    'lessonId'=>$user['lesson'],
                    'userId'=>$userId,
                    'userRoleId'=>$user['role']
                ]);
            }
        }
        return response()->json([
            'status'=>200,
            'msg'=>'成功上传'
        ]);
    }

    public function questionnaireExport(Request $request){
        $answerData = $request->answerViewData;
        $array = json_decode($answerData);
        $export = new questionnaireExport($array);
        return Excel::download($export,'问卷数据.xlsx');
    }
}
