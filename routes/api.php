<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::group(['prefix' => ''], function () {

    Route::post('register', 'Api\AuthController@register');
    Route::post('login', 'Api\AuthController@login');
    Route::get('/get-user', 'Api\AuthController@getUser');
    Route::post('update', 'Api\AuthController@update');
    Route::post('/active-account', 'Api\AuthController@activateAccount');
    Route::post('/resend-code', 'Api\AuthController@sendCode');
    Route::post('/request-password', 'Api\AuthController@requestPassword');
    Route::post('/reset-password', 'Api\AuthController@resetPassword');

    Route::get('/questions', 'Api\QuestionController@index');

    Route::post('/question-response', 'Api\QuestionResponseController@store');
    Route::get('/daily-winners', 'Api\QuestionResponseController@getDailyWinners');
    Route::get('/monthly-winners', 'Api\QuestionResponseController@getMonthlyWinners');

    Route::get('/get-certificate', 'Api\QuestionResponseController@getCertificate');
    Route::get('/get-result', 'Api\QuestionResponseController@getResult');
});