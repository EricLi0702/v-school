<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppTemplateController extends Controller
{
    //
    public function imageUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:gif,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/image'),$picName);
        return $picName;
    }

    public function otherUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:doc,docx,zip,pdf,xls,xlsx,rp,mp3,rp,ppt,pptx,pptm,apk,rar'
        ]);
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/other'),$fileName);
        return $fileName;
    }

    public function videoUpload(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:mp4'
        ]);
        $videoName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/video'),$videoName);
        return $videoName;
    }
}
