<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\History;
use App\UserCRM;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
// use File;

class FileController extends Controller
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
                if($request->has('month'))
                { 
                    $fileMonth = File::where('MONTH_FOLDER',$request->get('month'))->get();
                    return response()->json($fileMonth,200);
                }
                elseif ($request->has('folder'))
                {
                    # code...
                    $folderID = File::where('UUID_FOLDER_MANAGEMENT',$request->get('folder'))->get();
                    return response()->json($folderID, 200);
                }
                else
                {
                    $file = File::all();
                    return response()->json($file,200);
                }
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
                $thisYear = Carbon::now()->year;
                $thisMonth = Carbon::now()->month;
                $thisDay = Carbon::now()->day;
                $idFolder = $request->get('idFolder');
                $uuidFile = $request->get('idFile');
                $nameFolder = Folder::where('UUID_FOLDER_MANAGEMENT',$idFolder)->first('NAME_FOLDER');
                $nameFolder2 = collect($nameFolder)->first();
                $file = $request->file('nameFile');
                $nameFile = $file->getClientOriginalName();
                $cutNameFile = explode('.',$nameFile);
                $typeFile = end($cutNameFile);
                $file->move($thisYear.'/'.$nameFolder2.'/'.$typeFile.'/'.$thisMonth.'/',$nameFile);
                $pathFile = $typeFile.'/'.$thisMonth.'/'.$nameFile;
                $saveFile = new File();
                $saveFile->UUID_FILE_MANAGEMENT = $uuidFile;
                $saveFile->UUID_FOLDER_MANAGEMENT = $idFolder;
                $saveFile->PATH_FILE = $pathFile;
                $saveFile->TYPE_FILE = $typeFile;
                $saveFile->FOLDER_FILE = $nameFolder2;
                $saveFile->MONTH_FOLDER = $thisMonth;
                $saveFile->YEAR_FOLDER = $thisYear;
                $saveFile->NAME_FILE = $nameFile;
                $saveFile->save();
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "file",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa tạo file '.$saveFile->NAME_FILE
                ]);
                return response()->json($saveFile,200);
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if($request->has('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user)
            {
                $file = File::where('UUID_FILE_MANAGEMENT',$id)->first();
                if ($file) {
                    # code...
                    return response()->json($file,200);
                }else{
                    return response([
                        'error' => true,
                        'msg' => 'Không tìm thấy file'
                    ],404);
                }
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if($request->has('api_token')){
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if ($user) {
                # code...
                
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->has('api_token')) {
            # code...
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first(); 
            if ($user) {
                # code...	
                $file = File::where('UUID_FILE_MANAGEMENT',$id)->first();
                $path = $file->YEAR_FOLDER.'/'.$file->FOLDER_FILE.'/'.$file->TYPE_FILE.'/'.$file->MONTH_FOLDER.'/';
                unlink($path.$file->NAME_FILE);                
                $file_delete = File::where('UUID_FILE_MANAGEMENT',$id)->delete();
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "file",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa xóa file '.$file->NAME_FILE
                ]);
                return response()->json($file, 200);
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }
    
}
