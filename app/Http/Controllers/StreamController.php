<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
class StreamController extends Controller
{
    //
    public function getStream(Request $request){
        return Stream::all();
    }

    public function addStream(Request $request){
        $data = $request->data;
        $timeRange = $data['timeRange'];
        
        return Stream::create([
            'addData'=>json_encode($data),
            'timeRange'=>json_encode($timeRange)
        ]);
    }

}
