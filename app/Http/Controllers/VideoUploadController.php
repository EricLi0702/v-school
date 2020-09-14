<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VideoUpload;
use Illuminate\Support\Facades\Auth;

class VideoUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $videos = VideoUpload::orderBy('created_at', 'desc')->paginate(5);
        // return $videos;
        return VideoUpload::orderBy('created_at','desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadVideo(Request $request)
    {
        $this->validate($request,[
            'file' => 'required|mimes:mp4'
        ]);
        $videoName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads/video'),$videoName);
        return $videoName;
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'videoFile' => 'required',
            'description' => 'required',
            'grade' => 'required',
            'subject' => 'required',
        ]);
        // file_put_contents('test.txt',Auth::user()->id);
        // dd(Auth::user()->id);

        return VideoUpload::create([
                'userId' => Auth::user()->id,
                'videoFile' => $request->videoFile,
                'description' => $request->description,
                'grade' => $request->grade,
                'subject' => $request->subject,
            ]);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
