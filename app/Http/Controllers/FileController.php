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

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $file = File::all();
        return response()->json($file,200);
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
        
        //Xử lý
        if($request->get('api_token'))
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
                $saveFile->NAME_FILE = $nameFile;
                $saveFile->save();
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "user",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa tạo file '.$saveFile->NAME_FILE
                ]);
                return response()->json($saveFile,200);
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
        $thisMonth = Carbon::now()->month;
        $getFile = File::where('UUID_FILE_MANAGEMENT',$id)->first();
        $pathFile = $getFile->PATH_FILE;


        $cutPathFile = explode('/',$pathFile);
        $nameFile = end($cutPathFile);

        //Lấy phần mở rộng
        $cutNameFile = explode('.',$nameFile);
        // $fileDetail = $cutNameFile[1];
        return [$cutNameFile,$nameFile];
        // return [$fileDetail, $nameFile, $pathFile];
        // $file = File::where('UUID_FILE_MANAGEMENT',$id)->update([
        //     "PATH_FILE" => $fileDetail.'/'.$thisMonth.'/'.$request->get('nameFile').'/'.$fileDetail
        // ]);
        // // $file->PATH_NAME = $request->get();
        // // $file->save();
        // return response()->json($file,200);


        //Thay đổi tên file
        // $file->PATH_FILE = $request->get('nameFile');

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->get('api_token')) {
            # code...
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first(); 
            if ($user) {
                # code...
                $file = File::where('UUID_FILE',$id)->first();
            
                $file_delete = File::where('UUID_FILE',$id)->delete();
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "user",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa xóa file '.$file_delete->NAME_FILE
                ]);
                $path = $file->NAME_FILE;

                return response()->json($file, 200);
            }           
        }else{
            return response()->json('error', 401);
        }
    }
    
}
