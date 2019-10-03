<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserCRM;
use App\DetailFolder;
use App\History;
use Illuminate\Support\Str;
class DetailFolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has("api_token"))
        {
            $user = UserCRM::where("USER_TOKEN",$request->get("api_token"))->first();
            if($user)
            {
                if($request->has('status'))
                {
                        $detail = DetailFolder::join('crm_folder_management','crm_detail_user_folder.UUID_FOLDER_MANAGEMENT','crm_folder_management.UUID_FOLDER_MANAGEMENT')
                        ->where([
                            ["crm_detail_user_folder.UUID_USER",$user->UUID_USER],
                            ["STATUS",$request->get('status')]
                        ])->selected('crm_folder_management.NAME_FOLDER','crm_detail_user_folder.*')
                        ->orderBy("CREATED_AT",'DESC')->get();
                        return response()->json($detail, 200);
                }
                $details = DetailFolder::join("crm_user","crm_detail_user_folder.UUID_USER","crm_user.UUID_USER")
                ->where("crm_detail_user_folder.UUID_FOLDER_MANAGEMENT",$request->get("UUID_FOLDER_MANAGEMENT"))
                ->select("crm_user.UUID_USER","crm_user.USERNAME","crm_user.AVATAR")->get();
                return response()->json($details, 200);
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has("api_token"))
        {
            $user = UserCRM::where("USER_TOKEN",$request->get('api_token'))->first();
            if($user)
            {
                $array = explode(',', $request->get("UUIDS"));
                DetailFolder::where("UUID_FOLDER_MANAGEMENT",$request->get("UUID_FOLDER_MANAGEMENT"))->delete();
                foreach ($array as $UUID ) {
                    DetailFolder::create([
                        "UUID_FOLDER_MANAGEMENT" => $request->get("UUID_FOLDER_MANAGEMENT"),
                        "UUID_USER" => $UUID
                    ]);
                }
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "manager folder",
                    "NOTE_HISTORY" => $user->USERNAME.' đã thêm người vào quản lý thư mục'.$request->get('NAME_FOLDER')
                ]);
                return response()->json('success', 200);
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
    public function show($token,Request $request)
    {
        if($request->has('api_token'))
        {
           
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
