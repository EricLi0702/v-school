<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VideoUpload;
use Illuminate\Support\Facades\Auth;
use App\Events\NewVideoLectureUpload;

class VideoUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $videos = VideoUpload::orderBy('created_at', 'desc')->with('user')->paginate(2);
        // return $videos;
        return VideoUpload::orderBy('created_at','desc')->with('user')->paginate(5);
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

    public function viewedVideo(Request $request){
        $userId = Auth::user()->id;
        $videoId = $request->id;
        $videoData = VideoUpload::where('id', $videoId)->first();
        $currentViewCnt = $videoData->view_cnt;
        if($currentViewCnt == null){
            $currentViewCnt[] = $userId;
            $videoData->view_cnt = $currentViewCnt;
            $videoData->save();
            return $currentViewCnt;
        }
        else{
            if (in_array($userId, $currentViewCnt)) {
                return $currentViewCnt;
            }
            else{
                array_push($currentViewCnt, $userId);
                $videoData->view_cnt = $currentViewCnt;
                $videoData->save();
                return $currentViewCnt;
            }
        }
    }

    public function likedVideo(Request $request){
        $userId = Auth::user()->id;
        $videoId = $request->id;
        $videoData = VideoUpload::where('id', $videoId)->first();
        $currentLikeCnt = $videoData->like_cnt;
        if($currentLikeCnt == null){
            $currentLikeCnt[] = $userId;
            $videoData->like_cnt = $currentLikeCnt;
            $videoData->save();
            return $currentLikeCnt;
        }
        else{
            if (in_array($userId, $currentLikeCnt)) {
                return $currentLikeCnt;
            }
            else{
                array_push($currentLikeCnt, $userId);
                $videoData->like_cnt = $currentLikeCnt;
                $videoData->save();
                return $currentLikeCnt;
            }
        }
    }

    public function unLikedVideo(Request $request){
        $userId = Auth::user()->id;
        $videoId = $request->id;
        $videoData = VideoUpload::where('id', $videoId)->first();
        $currentLikeCnt = $videoData->like_cnt;
        if( sizeof($currentLikeCnt) == 1 && $currentLikeCnt[0] == $userId){
            $currentLikeCnt = null;
            $videoData->like_cnt = $currentLikeCnt;
            $videoData->save();
        }
        else{
            unset($currentLikeCnt[$userId]);
            $videoData->like_cnt = $currentLikeCnt;
            $videoData->save();
            return $currentLikeCnt;
        }
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'videoFile' => 'required',
            'description' => 'required',
            'grade' => 'required',
            'subject' => 'required',
            'title' => 'required',
        ]);
        // file_put_contents('test.txt',Auth::user()->id);
        // dd(Auth::user()->id);
        $data = request(['videoFile','description','grade', 'subject', 'title']);
        $userId = Auth::user()->id;
        $data['userId'] = $userId;
        $data['view_cnt'] = null;
        $data['like_cnt'] = null;
        $video = VideoUpload::create($data);

        //broadcast Event
        broadcast(new NewVideoLectureUpload($video->load('user')))->toOthers();

        return response()->json([
            'video' => $video->load('user')
        ], 201);
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
