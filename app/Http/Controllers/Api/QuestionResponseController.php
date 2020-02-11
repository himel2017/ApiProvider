<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\QuestionResponse;
use App\User;
use App\Helpers\StringHelper;
use App\Helpers\CorsHelper;
use App\Notifications\VerifyEmailContact;
use Carbon\Carbon;

class QuestionResponseController extends Controller
{

    function __construct()
    {
        CorsHelper::addCors();
        // date_default_timezone_set('Asia/Dhaka');
    }

    public function store(Request $request)
    {
        CorsHelper::addCors();
        $apiToken = $request->api_token;
        $user = User::select('id')->where('api_token', '=', $apiToken)->first();

        if(!is_null($user)){
            $user_id = $user->id;
        }else{
            return json_encode(['status' => false, 'message' => 'Sorry !! You are not an authenticated user !', 'questionResponse' => null]);
        }
        
        try {

            // Check if the user has already played a game today or not
            $questionResponse = QuestionResponse::where('user_id', $user_id)
            ->where('date', $request->date)->first();

            if(!is_null($questionResponse)){
                
                // Update time only the new time is less than previous time
                if($questionResponse->time > $request->time){
                    $questionResponse->time = $request->time;
                }
                $questionResponse->total_play = $questionResponse->total_play + 1;
                $questionResponse->save();
            }else{
                
                $slug = StringHelper::generateRandomString(10);

                $questionResponse = QuestionResponse::create([
                    'user_id' => $user_id,
                    'date' => date('Y-m-d'),
                    'time' => $request->time,
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                    'total_play' => 1,
                    'slug' => StringHelper::createSlug($slug, 'QuestionResponse', 'slug', ''),
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
            }
           

            return json_encode(['status' => true, 'message' => 'Question Response has been stored successfully !', 'questionResponse' => $questionResponse]);
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'questionResponse' => null]);
        }
    }

    public function getDailyWinners(Request $request)
    {
        CorsHelper::addCors();

        // Find last threee same min times
        $question_responses = DB::table('question_responses')
        ->select('*')
        ->where('date', '=', $request->date)
        ->limit(10)
        ->orderBy('time', 'asc')
        ->get()
        ->groupBy('time');

        // Get the winners of these time
        $winners = [];

        foreach ($question_responses as $response) {
            $time = $response[0]->time;

            $w = DB::table('question_responses')
            ->select('*')
            ->where('date', '=', $request->date)
            ->where('time', '=', $time)
            ->first();

            $w->winnerName = User::select('name')->whereId($w->user_id)->first();

            array_push($winners, $w);
        }

        return json_encode(['status' => true, 'message' => 'Success !! Daily Winner Lists',  'winners' => $winners]);

    }

    public function getMonthlyWinners(Request $request)
    {
        CorsHelper::addCors();

        // Find last threee same min times
        $question_responses = DB::table('question_responses')
        ->select('*')
        ->whereBetween('date', ['2020-02-01', '2020-02-29'])
        ->limit(3)
        ->orderBy('time', 'asc')
        ->get()
        ->groupBy('time');
        
        // Get the winners of these time
        $winners = [];

        foreach ($question_responses as $response) {
            $time = $response[0]->time;

            $w = DB::table('question_responses')
            ->select('*')
            ->whereBetween('date', ['2020-02-01', '2020-02-28'])
            ->where('time', '=', $time)
            ->first();

            $w->winnerName = User::select('name')->whereId($w->user_id)->first();

            array_push($winners, $w);
        }

        return json_encode(['status' => true, 'message' => 'Success !! Monthly Winner Lists',  'winners' => $winners]);
    }

    public function getCertificate(Request $request)
    {
        CorsHelper::addCors();

        $apiToken = $request->api_token;
        $user = User::select('id', 'name')->where('api_token', '=', $apiToken)->first();

        if(!is_null($user)){
            $user_id = $user->id;
        }else{
            return json_encode(['status' => false, 'message' => 'Sorry !! You are not an authenticated user !', 'questionResponse' => null]);
        }
        
        try {

            // Check if the user has already played a game today or not
            $questionResponse = QuestionResponse::where('user_id', $user_id)
            ->where('date', $request->date)->first();

            if(!is_null($questionResponse)){
                $questionResponse->name = $user->name;
                return json_encode(['status' => true, 'message' => 'Have Certificate', 'questionResponse' => $questionResponse]);
                
            }else{
                return json_encode(['status' => false, 'message' => 'No Certificate', 'questionResponse' => null]);
            }
           
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'questionResponse' => null]);
        }
    }

    public function getResult(Request $request)
    {
        CorsHelper::addCors();

        $slug = $request->slug;

        $result = QuestionResponse::select('*')->where('slug', '=', $slug)->first();

        if(!is_null($result)){
            $user_id = $result->user_id;
        }else{
            return json_encode(['status' => false, 'message' => 'Sorry !! You are not an authenticated user !', 'questionResponse' => null]);
        }
        
        try {

            // Check if the user has already played a game today or not
            $user = User::where('id', $user_id)->first();

            if(!is_null($user)){
                $result->name = $user->name;
                return json_encode(['status' => true, 'message' => 'Have Result', 'result' => $result]);
                
            }else{
                return json_encode(['status' => false, 'message' => 'No Result', 'result' => null]);
            }
           
        } catch (\Exception $e) {
            return json_encode(['status' => false, 'message' => $e->getMessage(), 'result' => null]);
        }
    }
}
