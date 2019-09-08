<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
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

        //Chuyển về không dấu
        function vn_to_str ($str){
 
            $unicode = array(
             
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
             
            'd'=>'đ',
             
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
             
            'i'=>'í|ì|ỉ|ĩ|ị',
             
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
             
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
             
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
             
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
             
            'D'=>'Đ',
             
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
             
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
             
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
             
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
             
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
             
            );
             
            foreach($unicode as $nonUnicode=>$uni){
             
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
             
            }
            $str = str_replace(' ','',$str);
             
            return $str;
             
            }

        //Xử lý
        $thisYear = Carbon::now()->year;
        $thisMonth = Carbon::now()->month;
        $idFolder = $request->get('idFolder');
        $nameFolder = Folder::where('UUID_FOLDER_MANAGEMENT',$idFolder)->first('NAME_FOLDER');
        $nameFolder2 = collect($nameFolder)->first();
        $nameFolderFormat = vn_to_str($nameFolder2);
        $file = $request->file('nameFile');
        $nameFile = $file->getClientOriginalName();
        $cutNameFile = explode('.',$nameFile);
        $typeFile = end($cutNameFile);

        $getFile = File::all();
        foreach ($getFile as $item) {
            # code...
            $checkFile = $item->PATH_FILE;
            $pathFile = $thisYear.'/'.$nameFolderFormat.'/'.$typeFile.'/'.$thisMonth.'/'.$nameFile;
            if ($checkFile != $pathFile) {
                # code...
                $file->move($thisYear.'/'.$nameFolder2.'/'.$typeFile.'/'.$thisMonth.'/',$nameFile);
                $saveFile = new File();
                $uuidFile = Str::uuid()->toString();
                $saveFile->UUID_FILE_MANAGEMENT = $uuidFile;
                $saveFile->UUID_FOLDER_MANAGEMENT = $idFolder;
                $saveFile->PATH_FILE = $pathFile;
                $saveFile->TYPE_FILE = $typeFile;
                $saveFile->FOLDER_FILE = $nameFolder2;
                $saveFile->MONTH_FOLDER = $thisMonth;
                $saveFile->save();
                return response()->json($saveFile,200);
            }else{
                return response(['msg' => 'File đã tồn tại'],200);
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
        //
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
        $file = File::where('UUID_FILE_MANAGEMENT',$id)->first();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = DB::table('crm_file_management')->where('UUID_FILE_MANAGEMENT','=',$id)->delete();
        return response([
            'error' => false,
            'msg' => 'Xóa thành công'
        ],200);
    }
    
}
