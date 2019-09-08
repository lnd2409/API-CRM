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
//Route::get('folder/{id}','FolderController@show); // sửa lại thnafh thến ày nhá
Route::get('folder/edit/{id}','FolderController@edit'); //get nhưng sao lại edit thế: em lấy cái file đó ra để edit còn update để sửa
// này nữa
// => Route::póst('folder','FolderController@store');
Route::post('folder', 'FolderController@store');
// Route::post('folder/{id}/update','FolderController@update')
Route::put('update-folder/{id}', 'FolderController@update');
// Route::delete('folder/{id}','FolderController@destroy')
Route::delete('delete-folder/{id}', 'FolderController@destroy');
// => thống nhất là 1 kiểu như thế nha 
/* File */
Route::get('file','FileController@index');
Route::get('file/edit/{id}','FileController@edit');
Route::post('create-file', 'FileController@store');
Route::put('update-file/{id}', 'FileController@update');
Route::delete('delete-file/{id}', 'FileController@destroy');
