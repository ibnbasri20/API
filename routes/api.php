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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/registration', 'API\Auth@store');
Route::post('/auth/login', 'API\Auth@login');

Route::post('/event', 'API\EventAPI@store')->middleware('api.auth');
Route::get('/event', 'API\EventAPI@index')->middleware('api.auth');
Route::get('/event/{id}', 'API\EventAPI@show')->middleware('api.auth');
Route::get('/event/join/{id}', 'API\EventAPI@join')->middleware('api.auth');

Route::post('/msg', 'API\ChatAPI@sendchat')->middleware('api.auth');
Route::get('/msg', 'API\ChatAPI@getLatest')->middleware('api.auth');
Route::post('/chat/group', 'API\ChatAPI@send_group_chat')->middleware('api.auth');
Route::get('/chat/group', 'API\ChatAPI@get_latest_group')->middleware('api.auth');
Route::get('/chat/group/{id}', 'API\ChatAPI@get_latest_chat_group')->middleware('api.auth');
