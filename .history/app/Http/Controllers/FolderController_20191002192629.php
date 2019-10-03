<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Folder;
use App\History;
use App\UserCRM;
use App\DetailFolder;
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
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user)
            {
                if($request->has('year')){ 
                    if($user->UUID_RULE != 'coder-2019')
                    {
                        
                        if($request->has('UUID_PARENT')){
                            // $folderYear = Folder::where([
                            //     ['YEAR_FOLDER',$request->get('year')],
                            //     ['UUID_PARENT',$request->get('UUID_PARENT')]])->get();
                            // return response()->json($folderYear,200);
                            $folderYear = Folder::join('crm_detail_user_folder','crm_folder_management.UUID_FOLDER_MANAGEMENT','crm_detail_user_folder.UUID_FOLDER_MANAGEMENT')
                                ->where([
                                ['crm_folder_management.YEAR_FOLDER',$request->get('year')],
                                ['crm_detail_user_folder.UUID_USER',$user->UUID_USER],
                                ['crm_folder_management.UUID_PARENT',$request->get("UUID_PARENT")]])->get();
                            return response()->json($folderYear,200);
                        }
                        $folderYear = Folder::join('crm_detail_user_folder','crm_folder_management.UUID_FOLDER_MANAGEMENT','crm_detail_user_folder.UUID_FOLDER_MANAGEMENT')
                        ->where([
                            ['crm_folder_management.YEAR_FOLDER',$request->get('year')],
                            ['crm_detail_user_folder.UUID_USER',$user->UUID_USER]])->get();
                        return response()->json($folderYear,200);
                    }
                    else if($request->has('check_path'))
                    {
                        $folder = Folder::where([
                            ["SAFE_FOLDER",$request->get('check_path')],
                            ["YEAR_FOLDER",$request->get('year')]
                        ])->first();
                        if($folder)
                        {
                            return response()->json(false, 200);
                        }
                        return response()->json(true, 200);
                    }
                    else if($request->has('UUID_PARENT')){
                        $folderYear = Folder::where([
                            ['YEAR_FOLDER',$request->get('year')],
                            ['UUID_PARENT',$request->get('UUID_PARENT')]])->get();
                        return response()->json($folderYear,200);
                    }
                    $folderYear = Folder::where([
                        ['YEAR_FOLDER',$request->get('year')],
                        ['UUID_PARENT', null]])->get();
                    return response()->json($folderYear,200);
                }
                
                else{
                    $folder = Folder::all();
                    return response()->json($folder, 200);
                }
            }
            return response()->json(false,404);
        }
        $file = Storage::files(public_path('upload/avatar/'));
        return response()->json($file,200);        
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
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user)
            {
                $parent = null;
                if($request->has("UUID_PARENT"))
                {
                    $parent =  $request->get("UUID_PARENT");
                }
                $folder = Folder::create([
                    "UUID_FOLDER_MANAGEMENT" => Str::uuid(),
                    "NAME_FOLDER" => $request->get("NAME_FOLDER"),
                    "SAFE_FOLDER" => $request->get("SAFE_FOLDER"),
                    "YEAR_FOLDER" => $request->get("YEAR_FOLDER"),
                    "UUID_PARENT" => $parent
                ]);
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "folder",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa tạo folder '.$folder->NAME_FOLDER
                ]);
                return response()->json($request->all(), 200);
            }
            return response()->json(false,404);
        }
        return response()->json(false,401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->first();
                if ($folder) {
                    return response()->json($folder, 200);
                }
                else
                {
                    $error = 'Không tìm thấy thư mục';
                    return response()->json($error,404);
                }
            }
            return response()->json(false,404);
        }
        return response()->json(false,401);
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
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $parent = null;
                $lv = 1;
                if($request->has('UUID_PARENT'))
                {
                    $parent = $request->get('UUID_PARENT');
                }
                if($request->has('LV_FOLDER'))
                {
                    $parent = $request->get('LV_FOLDER');
                }
                $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->update([
                    "NAME_FOLDER" => $request->get('NAME_FOLDER'),
                    "SAFE_FOLDER" => $request->get("SAFE_FOLDER"),
                    "YEAR_FOLDER" => $request->get("YEAR_FOLDER"),
                    "LV_FOLDER" => $lv,
                    "UUID_PARENT" => $parent]);
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "folder",
                    "NOTE_HISTORY" => $user->USERNAME.' vua cap nhat folder '.$request->get('NAME_FOLDER')
                ]);
                return response()->json($folder,200);
            }
            return response()->json(false,404);
        }
        return response()->json(false,401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $folder = Folder::join('crm_file_management',
                                'crm_file_management.UUID_FOLDER_MANAGEMENT',
                                '=', 'crm_file_management.UUID_FOLDER_MANAGEMENT')
                            ->where('crm_file_management.UUID_FOLDER_MANAGEMENT','=',$id)
                            ->first();
                if ($folder) {
                    // trường hợp có file tồn tại trong folder
                    $file = $folder->UUID_FILE_MANAGEMENT;
                    $getFile = File::where('UUID_FILE_MANAGEMENT',$file)->get();
                    return response($getFile,200);
                }else{
                    // không có file trong hệ thống
                    $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->delete();
                    History::create([
                        "UUID_USER" => $user->UUID_USER,
                        "UUID_HISTORY" => Str::uuid(),
                        "NAME_HISTORY" => "folder",
                        "NOTE_HISTORY" => $user->USERNAME.' vừa xóa folder '
                    ]);
                    return response()->json($folder, 200);
                }
            }
            return response()->json(false,404);
        }
        return response()->json(false,401);
    }
}
