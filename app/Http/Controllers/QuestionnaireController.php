<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Questionnaire;
class QuestionnaireController extends Controller
{
    //
    public function getLists(){
        return Questionnaire::all();
    }
}
