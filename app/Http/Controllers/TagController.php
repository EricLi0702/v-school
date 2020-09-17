<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
class TagController extends Controller
{
    //
    public function getTag(){
        return Tag::orderBy('id','desc')->get();
    }

    public function addTag(Request $request){
        $this->validate($request,[
            'tagName' => 'required'
        ]);
        return Tag::create([
                'tagName' => $request->tagName
            ]);
    }

    public function deleteTag(Request $request){
        $this->validate($request,[
            'tagName'=>'required',
            'id' => 'required'
        ]);
        return Tag::where('id',$request->id)->delete();
    }

    public function editTag(Request $request){
        $this->validate($request,[
            'tagName'=>'required',
            'id' => 'required'
        ]);
        return Tag::where('id',$request->id)->update([
            'tagName'=>$request->tagName
        ]);
    }
}
