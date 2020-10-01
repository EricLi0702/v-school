<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\BulletinBoard;
class CommentController extends Controller
{
    //
    public function addComment(Request $request){
        $bulletinId = $request->bulletinId;
        $userId = $request->userId;
        $comment = $request->comment;
        $id = Comment::create([
            'bulletinId'=>$bulletinId,
            'userId'=>$userId,
            'comment'=>$comment
        ])->id;
        $data = Comment::where('id',$id)->with('user')->get();
        return response()->json($data,201);
    }
    
    public function getComment(Request $request){
        $bulletinId = $request->bulletinId;
        $data = Comment::where('bulletinId',$bulletinId)->with('user')->orderBy('created_at','desc')->get();
        return response()->json($data,200);
    }

    public function deleteComment(Request $request){
        return Comment::where('id',$request->id)->delete();
    }

}


