<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
use DateTime;
class StreamController extends Controller
{
    //
    public function getStream(Request $request){
        return Stream::orderBy('from','asc')->get();
    }

    public function addStream(Request $request){
        $data = $request->data;
        $timeRange = $data['timeRange'];
        $from = $timeRange[0];
        $to = $timeRange[1];
        $requestedFrom = new DateTime($from);
        $requestedTo = new DateTime($to);
        
        $streamData = Stream::where([['from','<', $from ],['to','>',$from]])->orwhere([['from','>',$from],['from','<',$to]])->get();
        $count = count($streamData);
        if($count>0){
            return response()->json('服务器上有预定时间',400);
        }
        return Stream::create([
            'addData'=>json_encode($data),
            'from'=>$from,
            'to'=>$to
        ]);
    }

}
