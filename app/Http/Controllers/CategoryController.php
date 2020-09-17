<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
class CategoryController extends Controller
{
    //
    public function uploadFile(Request $request){
        $this->validate($request,[
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }

    public function deleteFile(Request $request){
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }

    public function deleteFileFromServer($fileName){
        $filePath = public_path().$fileName;
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }

    public function addCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        file_put_contents('test.txt',$request);
        return Category::create([
                'categoryName' => $request->categoryName,
                'iconImage' => $request->iconImage
            ]);
        
    }

    public function getCategory(){
        return Category::orderBy('id','desc')->get();
    }

    public function deleteCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'id' => 'required'
        ]);
        return Category::where('id',$request->id)->delete();
    }

    public function editCategory(Request $request){
        $this->validate($request,[
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::where('id',$request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
}
