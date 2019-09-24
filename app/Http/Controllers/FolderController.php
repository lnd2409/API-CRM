<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\History;
use App\UserCRM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('year')){ 
            $folderYear = Folder::where('YEAR_FOLDER',$request->get('year'))->get();
            return response()->json($folderYear,200);
        }
        else{
        $folder = Folder::all();
        return response()->json($folder, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $folder = Folder::create($request->all());
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "user",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa tạo folder '.$folder->NAME_FOLDER
                ]);
                return response()->json($folder, 200);
            }
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->first();
        if ($folder) {
            # code...
            return response()->json($folder, 200);
        }else{
            $error = 'Không tìm thấy thư mục';
            return response()->json($error,404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->update([
                    "NAME_FOLDER" => $request->NAME_FOLDER
                ]);
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "user",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa cập nhật folder '.$folder->NAME_FOLDER
                ]);
                return response()->json($folder,200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $folder = Folder::join('crm_file_management',
                                'crm_file_management.UUID_FOLDER_MANAGEMENT',
                                '=', 'crm_file_management.UUID_FOLDER_MANAGEMENT')
                            ->where('crm_file_management.UUID_FOLDER_MANAGEMENT','=',$id)
                            ->first();
                // $file = $folder->UUID_FILE_MANAGEMENT;
                if ($folder) {
                    // trường hợp có file tồn tại trong folder
                    $file = $folder->UUID_FILE_MANAGEMENT;
                    $getFile = File::where('UUID_FILE_MANAGEMENT',$file)->get();
                    return response($getFile,200);
                }else{
                    // không có file trong hệ thống
                    $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->delete();
                    return response()->json($folder, 200);
                }
            }
        }
    }
}
