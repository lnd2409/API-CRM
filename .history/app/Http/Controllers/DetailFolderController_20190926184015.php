<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserCRM;
use App\DetailFolder;
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
                DetailFolder::join("crm_user","crm_folder_management.UUID_USER","crm_user.UUID_USER")->where("UUID_FOLDER_MANAGEMENT",$request->get("UUID_FOLDER_MANAGEMENT"))
                ->select("crm_user.UUID_USER","USERNAME","AVATAR")->get();
            }
        }
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
                foreach ($request->get("UUIDS") as $UUID ) {
                    DetailFolder::create([
                        "UUID_FOLDER_MANAGEMENT" => $request->get("UUID_FOLDER_MANAGEMENT"),
                        "UUID_USER" => $UUID
                    ]);
                }
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
    public function show($id)
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
