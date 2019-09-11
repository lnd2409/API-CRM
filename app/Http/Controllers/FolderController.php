<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use Carbon\Carbon;
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
        return response()->json($folder, 200);
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
        $folder = Folder::where('UUID_FOLDER_MANAGEMENT',$id)->update([
            "NAME_FOLDER" => $request->NAME_FOLDER
        ]);
        return response()->json($folder,200);
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
