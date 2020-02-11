<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use App\Helpers\CorsHelper;

class QuestionController extends Controller
{
    function __construct()
    {
        CorsHelper::addCors();
    }

    public function index(Request $request)
    {
        CorsHelper::addCors();

        if(isset($request->set_no)){
            $questions = Question::where('set_no', $request->set_no)
            ->orderBy('id', 'asc')
            ->get();
        }else{
            $questions = Question::orderBy('id', 'asc')->get();
        }
        return json_encode(['status' => true, 'message' => 'Success !! Question Lists',  'questions' => $questions]);
    }
}
