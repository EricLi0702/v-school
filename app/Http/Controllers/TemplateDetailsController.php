<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TemplateDetails;
class TemplateDetailsController extends Controller
{
    //
    public function storeSingleContent(Request $request){
        $this->validate($request,[
            'type'=>'required',
            'contentData'=>'required'
        ]);
        $contentType = $request->type;
        $contentData = json_encode($request->contentData);
        return TemplateDetails::create([
            'contentType'=>$contentType,
            'questions'=>$contentData
        ]);
    }
}
