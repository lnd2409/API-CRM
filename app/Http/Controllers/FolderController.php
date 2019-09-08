<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use DB;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folder = Folder::all();
        return response()->json($folder, 200);
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
        $folder = Folder::create($request->all());
        // $folder = new Folder();
        // $thisYear = Carbon::now()->year;
        // $uuid = Str::uuid()->toString();
        // $folder->UUID_FOLDER_MANAGEMENT = $uuid;
        // $folder->NAME_FOLDER = $request->get('name_folder');
        // $folder->YEAR_FOLDER = $thisYear;
        // $folder->save();
        return response()->json($folder, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->first();
        if ($folder) {
            # code...
            return response()->json($folder, 200);
        }else{
            $error = 'Không tìm thấy thư mục';
            return response()->json($error,200);
        }
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
        $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->update([
            "NAME_FOLDER" => $request->get("NAME_FOLDER")
        ]);
        // if ($folder) {
            # code...
            // $namePath = File::where('UUID_FOLDER_MANAGEMENT',$id)->first('PATH_FILE');
            // $formatNamePath = collect($namePath)->first();
            // $namePath2 = substr($formatNamePath,5);
            // // $updatePathFile = File::where('UUID_FOLDER_MANAGEMENT',$id)->update([
            // //     'PATH_FILE' => $request->get('NAME_FOLDER')
            // // ]);
            // $namePath3 = explode('/', $namePath2);
            
            return response()->json($folder,200);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Còn di chuyển những file trong thư mục qua thư mục trống
        $folder = DB::table('crm_folder_management')->where('UUID_FOLDER_MANAGEMENT','=',$id)->delete();
        return response([
            'error' => false,
            'msg' => 'Xóa thành công'
        ],200);
    }
}
