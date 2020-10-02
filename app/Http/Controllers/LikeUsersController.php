<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LikeUsers;
class LikeUsersController extends Controller
{
    //
    public function addIsLiked(Request $request){
        $bulletinId = $request->bulletinId;
        $userId = $request->userId;
        return LikeUsers::create([
            'userId'=>$userId,
            'bulletinId'=>$bulletinId
        ]);
    }

    public function delIsLiked(Request $request){
        $bulletinId = $request->bulletinId;
        $userId = $request->userId;
        return LikeUsers::where(['userId'=>$userId,'bulletinId'=>$bulletinId])->delete();
    }
}
