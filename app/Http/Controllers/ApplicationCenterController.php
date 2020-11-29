<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationCenterController extends Controller
{
    //
    public function fileImport(Request $request) 
    {
        Excel::import(new UsersImport, $request->file('file')->store('temp'));
        return back();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileExport() 
    {
        return Excel::download(new UsersExport, 'users-collection.xlsx');
    }
}
