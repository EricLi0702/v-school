<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grade;
use App\Lesson;
use App\BulletinBoard;

class GradeController extends Controller
{
    //
    public function getGrade(Request $request){
        $this->validate($request,[

        ]);
        return Grade::with('schools')->get();
    }

    public function storeGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::create([
            'gradeName'=>$request->gradeName,
            'imgUrl'=>$request->imgUrl,
            'schoolId'=>$request->schoolId
        ]);
    }

    public function updateGrade(Request $request){
        $this->validate($request,[
            'gradeName'=>'required',
            'imgUrl'=>'required',
            'schoolId'=>'required'
        ]);
        return Grade::where('id',$request->id)->update([
            'gradeName'=>$request->gradeName,
            'imgUrl'=>$request->imgUrl,
            'schoolId'=>$request->schoolId
        ]);
    }

    public function removeGrade(Request $request){
        $this->validate($request,[
            'id'=>'required'
        ]);
        return Grade::where('id',$request->id)->delete();
    }

    public function gradeByClassName(Request $request){
        $classId = $request->classId;
        $school = Lesson::where('id',$classId)->get();
        $schoolId = $school[0]->schoolId;
        return Grade::where('schoolId',$schoolId)->get();
    }

    public function getAblum(Request $request)
    {   
        $albumData = [];
        $classId = (int)($request->id);
        $gradeId = Lesson::where('id', $classId)->first()->gradeId;
        $bulletinBoardDataList = BulletinBoard::all();
        foreach ($bulletinBoardDataList as $key => $bulletinBoardData){
            $contentType = $bulletinBoardData->contentType;
            $addData = json_decode($bulletinBoardData->addData);
            if (isset($addData->viewList)){
                $viewList = $addData->viewList;
                if(end($viewList) == $gradeId){
                    $removedGrade = array_pop($viewList);
                    $contentOfBullet = $addData->content;
                    if (in_array($classId, $viewList)){
                        switch ($contentType){
                            case 1:
                                {
                                    //singleContentDataArr
                                    $singleContentDataArr = $contentOfBullet->singleContentDataArr;
                                    foreach ($singleContentDataArr as $key => $singleContentData){
                                        foreach ($singleContentData as $key => $singleContent){
                                            $asdf = $singleContent;
                                            $asdf = $singleContent;
                                            $imageData = $singleContent->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                    //multiContentDataArr
                                    $multiContentDataArr = $contentOfBullet->multiContentDataArr;
                                    foreach ($multiContentDataArr as $key => $multiContentData){
                                        foreach ($multiContentData as $key => $multiContent){
                                            $asdf = $multiContent;
                                            $asdf = $multiContent;
                                            $imageData = $multiContent->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                    //questionAnswerDataArr
                                    $questionAnswerDataArr = $contentOfBullet->questionAnswerDataArr;
                                    foreach ($questionAnswerDataArr as $key => $questionAnswerData){
                                        foreach ($questionAnswerData as $key => $questionAnswer){
                                            $asdf = $questionAnswer;
                                            $asdf = $questionAnswer;
                                            $imageData = $questionAnswer->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                    //statisticsDataArr
                                    $statisticsDataArr = $contentOfBullet->statisticsDataArr;
                                    foreach ($statisticsDataArr as $key => $statisticsData){
                                        foreach ($statisticsData as $key => $statistics){
                                            $asdf = $statistics;
                                            $asdf = $statistics;
                                            $imageData = $statistics->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                    //scoringQuestoinsDataArr
                                    $scoringQuestoinsDataArr = $contentOfBullet->scoringQuestoinsDataArr;
                                    foreach ($scoringQuestoinsDataArr as $key => $scoringQuestoinsData){
                                        foreach ($scoringQuestoinsData as $key => $scoringQuestoins){
                                            $asdf = $scoringQuestoins;
                                            $asdf = $scoringQuestoins;
                                            $imageData = $scoringQuestoins->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                }
                                break;
                            case 2:
                                {
                                    //votingDataArr
                                    $votingDataArr = $contentOfBullet->votingDataArr;
                                    foreach ($votingDataArr as $key => $votingData){
                                        foreach ($votingData as $key => $votingContent){
                                            $asdf = $votingContent;
                                            $asdf = $votingContent;
                                            $imageData = $votingContent->imgUrl;
                                            foreach ($imageData as $key => $imgUrl){
                                                array_push($albumData, $imgUrl);
                                            }
                                        }
                                    }
                                }
                                break;
                            case 19:
                                {
                                    $imageData = $contentOfBullet->imgUrl;
                                    foreach ($imageData as $key => $imgUrl){
                                        array_push($albumData, $imgUrl);
                                    }
                                }
                                break;
                        }
                        
                        
                    }
                }
            }
            else{
                switch ($contentType){
                    case 3:
                        {
                            $imageData = $addData->imgUrl;
                            foreach ($imageData as $key => $imgUrl){
                                array_push($albumData, $imgUrl);
                            }
                        }
                    break;
                    case 7:
                        {
                            $imageData = $addData->imgUrl;
                            foreach ($imageData as $key => $imgUrl){
                                array_push($albumData, $imgUrl);
                            }
                        }
                    break;
                    case 15:
                        {
                            $imageData = $addData->imgUrl;
                            foreach ($imageData as $key => $imgUrl){
                                array_push($albumData, $imgUrl);
                            }
                        }
                    break;
                    case 18:
                        {
                            $imageData = $addData->content->imgUrl;
                            foreach ($imageData as $key => $imgUrl){
                                array_push($albumData, $imgUrl);
                            }
                        }
                    break;
                    case 4:
                        {
                            array_push($albumData, $addData->imgUrl);
                        }
                    break;
                }
                
            }
        }

        return response()->json([
            'albumData' => $albumData
            // 'albumData' => []
        ], 200);
        
    }

    public function getFile(Request $request)
    {   
        $fileDataArr = [];
        $classId = (int)($request->id);
        $gradeId = Lesson::where('id', $classId)->first()->gradeId;
        $bulletinBoardDataList = BulletinBoard::all();
        foreach ($bulletinBoardDataList as $key => $bulletinBoardData){
            $contentType = $bulletinBoardData->contentType;
            $addData = json_decode($bulletinBoardData->addData);
            if (isset($addData->viewList)){
                $viewList = $addData->viewList;
                if(end($viewList) == $gradeId){
                    $removedGrade = array_pop($viewList);
                    $contentOfBullet = $addData->content;
                    if (in_array($classId, $viewList)){
                        switch ($contentType){
                            case 1:
                                {
                                    //singleContentDataArr
                                    $singleContentDataArr = $contentOfBullet->singleContentDataArr;
                                    foreach ($singleContentDataArr as $key => $singleContentData){
                                        foreach ($singleContentData as $key => $singleContent){
                                            $fileData = $singleContent->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                    //multiContentDataArr
                                    $multiContentDataArr = $contentOfBullet->multiContentDataArr;
                                    foreach ($multiContentDataArr as $key => $multiContentData){
                                        foreach ($multiContentData as $key => $multiContent){
                                            $fileData = $multiContent->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                    //questionAnswerDataArr
                                    $questionAnswerDataArr = $contentOfBullet->questionAnswerDataArr;
                                    foreach ($questionAnswerDataArr as $key => $questionAnswerData){
                                        foreach ($questionAnswerData as $key => $questionAnswer){
                                            $fileData = $questionAnswer->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                    //statisticsDataArr
                                    $statisticsDataArr = $contentOfBullet->statisticsDataArr;
                                    foreach ($statisticsDataArr as $key => $statisticsData){
                                        foreach ($statisticsData as $key => $statistics){
                                            $fileData = $statistics->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                    //scoringQuestoinsDataArr
                                    $scoringQuestoinsDataArr = $contentOfBullet->scoringQuestoinsDataArr;
                                    foreach ($scoringQuestoinsDataArr as $key => $scoringQuestoinsData){
                                        foreach ($scoringQuestoinsData as $key => $scoringQuestoins){
                                            $fileData = $scoringQuestoins->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                }
                                break;
                            case 2:
                                {
                                    //votingDataArr
                                    $votingDataArr = $contentOfBullet->votingDataArr;
                                    foreach ($votingDataArr as $key => $votingData){
                                        foreach ($votingData as $key => $votingContent){
                                            $fileData = $votingContent->otherUrl;
                                            foreach ($fileData as $key => $other){
                                                array_push($fileDataArr, $other);
                                            }
                                        }
                                    }
                                }
                                break;
                            case 19:
                                {
                                    $fileData = $contentOfBullet->otherUrl;
                                    foreach ($fileData as $key => $other){
                                        array_push($fileDataArr, $other);
                                    }
                                }
                                break;
                        }
                        
                        
                    }
                }
            }
            else{
                switch ($contentType){
                    case 3:
                        {
                            $fileData = $addData->otherUrl;
                            foreach ($fileData as $key => $other){
                                array_push($fileDataArr, $other);
                            }
                        }
                    break;
                    case 7:
                        {
                            $fileData = $addData->otherUrl;
                            foreach ($fileData as $key => $other){
                                array_push($fileDataArr, $other);
                            }
                        }
                    break;
                    case 15:
                        {
                            $fileData = $addData->otherUrl;
                            foreach ($fileData as $key => $other){
                                array_push($fileDataArr, $other);
                            }
                        }
                    break;
                    case 18:
                        {
                            $fileData = $addData->content->otherUrl;
                            foreach ($fileData as $key => $other){
                                array_push($fileDataArr, $other);
                            }
                        }
                    break;
                }
                
            }
        }

        return response()->json([
            'fileData' => $fileDataArr
            // 'fileData' => []
        ], 200);
        
    }
}
