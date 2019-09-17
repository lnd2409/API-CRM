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


/* Folder */
//e đặt theo kiểu này nè
Route::get('folder','FolderController@index');
Route::get('folder/{id}','FolderController@show'); // sửa lại thnafh thến ày nhá
Route::post('folder/create', 'FolderController@store');
Route::post('folder/{id}/update','FolderController@update');
Route::delete('folder/{id}/delete', 'FolderController@destroy');
// => thống nhất là 1 kiểu như thế nha 

//em đừng lo cái vụ uuid đầu vào, khi a call sẽ truyền nó vô à  năm vẫn thế 
// nhưng đối ới phần file thì phải dùm formData() tại file thì không dùn json được 
/* File */
Route::get('file','FileController@index');
Route::get('file/{id}','FileController@shows');
Route::post('file/create', 'FileController@store');
Route::post('file/{id}/update', 'FileController@update');
Route::delete('file/{id}/delete', 'FileController@destroy');

/* Rule */
Route::get('rule','RuleController@index');
Route::get('rule/{id}','RuleController@shows');
Route::post('rule/create', 'RuleController@store');
Route::post('rule/{id}/update', 'RuleController@update');
Route::delete('rule/{id}/delete', 'RuleController@destroy');

/* Comment */
Route::get('comment','CommentController@index');
Route::get('comment/{id}','CommentController@shows');
Route::post('comment/create', 'CommentController@store');
Route::post('comment/{id}/update', 'CommentController@update');
Route::delete('comment/{id}/delete', 'CommentController@destroy');

/* User */
Route::get('user','UserCRMController@index');
Route::get('user/{id}','UserCRMController@shows');
Route::post('user/create', 'UserCRMController@store');
Route::post('user/{id}/update', 'UserCRMController@update');
Route::delete('user/{id}/delete', 'UserCRMController@destroy');