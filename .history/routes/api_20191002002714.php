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


/* Folder */
Route::get('folder','FolderController@index');
Route::get('folder/{id}','FolderController@show');
Route::post('folder/create', 'FolderController@store');
Route::post('folder/{id}/update','FolderController@update');
Route::post('folder/{id}/delete', 'FolderController@destroy');
/* manager folder */
Route::get('manager/folder','DetailFolderController@index');
Route::post('manager/folder/create','DetailFolderController@store');
/* File */
Route::get('file','FileController@index');
Route::get('file/{id}','FileController@shows');
Route::post('file/create', 'FileController@store');
Route::post('file/{id}/update', 'FileController@update');
Route::post('file/{id}/delete', 'FileController@destroy');
Route::get('download/{id}', 'FileController@dowload');
/* Rule */
Route::get('rule','RuleController@index');
Route::get('rule/{id}','RuleController@shows');
Route::post('rule/create', 'RuleController@store');
Route::post('rule/{id}/update', 'RuleController@update');
Route::post('rule/{id}/delete', 'RuleController@destroy');

/* Comment */
Route::get('comment','CommentController@index');
Route::get('comment/{id}','CommentController@shows');
Route::post('comment/create', 'CommentController@store');
Route::post('comment/{id}/update', 'CommentController@update');
Route::post('comment/{id}/delete', 'CommentController@destroy');

/* User */
Route::get('user','UserCRMController@index');
Route::get('user/{id}','UserCRMController@show');
Route::get('notify','UserCRMController@NotifyFirebase');
Route::post('user/create', 'UserCRMController@store');
Route::post('user/{id}/update', 'UserCRMController@update');
Route::post('user/{id}/delete', 'UserCRMController@destroy');
Route::post('user/login','UserCRMController@loginUser');
Route::post('user/logout', 'UserCRMController@logoutUser');
Route::post('user/check', 'UserCRMController@checkUsername');

Route::get("history",'UserCRMController@history');
// parable
Route::get('parable','parableController@index');
Route::post('parable/create','parableController@store');

/*socket*/
Route::get('socket','SocketController@index')''
