<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BulletinBoard;
class BulletinBoardController extends Controller
{
    //
    public function addQuestionnaire(Request $request){
        $data = $request->data;
        $content = json_encode($data);
        $userId = $request->userId;
        // return BulletinBoard::create([

        // ]);
    }
}
