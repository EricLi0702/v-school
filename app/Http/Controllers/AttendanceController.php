<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use Carbon\Carbon;
class AttendanceController extends Controller
{
    //
    public function index(){
        
    }

    public function getAttendanceData(Request $request){
        $date = $request->date;
        // $startDate = new Carbon($date[0]);
        // $endDate = new Carbon($date[1]);
        // $all_dates = array();
        // $data = array();
        // while ($startDate->lte($endDate)){
        //     $whereDate = $startDate->toDateString();
        //     $fetchData = Attendance::select('scheduleTimes','workedTimes','lateCnt','earlyLeaveCnt','sickCnt','tripCnt','userId')
        //                             ->where('workday',$whereDate)
        //                             ->with('user')
        //                             ->orderBy('workday','asc')
        //                             ->orderBy('userId','asc')
        //                             ->get();
        //     if(sizeof($fetchData) != 0){
        //         $all_dates['date'] = $whereDate;
        //         $all_dates['data'] = $fetchData;
        //         $data[] = $all_dates;
        //     }
        //     $startDate->addDay();
        // }
        $data = Attendance::select('scheduleTimes','workedTimes','lateCnt','earlyLeaveCnt','sickCnt','absentCnt','tripCnt','workday','userId')
                                ->whereBetween('workday',[$date[0],$date[1]])
                                ->with('user')
                                ->orderBy('workday','asc')
                                ->orderBy('userId','asc')
                                ->get();
        return response()->json($data);
    }

    public function getAttendanceMonth(Request $request){
        $date = $request->date;
        $month = new Carbon($date);
        $data = Attendance::select('scheduleTimes','workedTimes','lateCnt','earlyLeaveCnt','sickCnt','absentCnt','tripCnt','workday','userId')
                                ->whereMonth('workday',$month)
                                ->with('user')
                                ->orderBy('workday','asc')
                                ->orderBy('userId','asc')
                                ->get();
        return response()->json($data);
    }

    public function getAttendanceYear(Request $request){
        $date = $request->date;
        // $year = new Carbon($date);
        $data = Attendance::select('scheduleTimes','workedTimes','lateCnt','earlyLeaveCnt','sickCnt','absentCnt','tripCnt','workday','userId')
                                ->whereYear('workday',$date)
                                ->with('user')
                                ->orderBy('workday','asc')
                                ->orderBy('userId','asc')
                                ->get();
        return response()->json($data);
    }

    public function getAttendanceDetail(Request  $request){
        $date = $request->date;
        $id = $request->name;
        // $user = User::where('name',$name)->get();
        $data = Attendance::select('scheduleTimes','workedTimes','lateCnt','earlyLeaveCnt','sickCnt','absentCnt','tripCnt','workday','userId')
                                ->whereBetween('workday',[$date[0],$date[1]])
                                ->where('userId',$id)
                                ->with('user')
                                ->orderBy('workday','asc')
                                ->orderBy('userId','asc')
                                ->get();
        return response()->json($data);
    }
}
