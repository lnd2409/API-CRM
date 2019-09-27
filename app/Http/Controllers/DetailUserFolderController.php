<?php

namespace App\Http\Controllers;

use App\DetailUserFolder;
use App\UserCRM;
use Illuminate\Http\Request;

class DetailUserFolderController extends Controller
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
            if($user){
                $getUser = DetailUserFolder::all();
            }
            return response()->json(false,404);
        }
        return response()->json(false,401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('api_token')) {
            # code...
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if ($user) {
                # code...
                $uuidUser = $request->get('UUID_USER');
                $uuidFolder = $request->get('UUID_FOLDER');
                $data = new DetailUserFolder();
                $data->UUID_USER = $uuidUser;
                $data->UUID_FOLDER_MANAGEMENT = $uuidFolder;
                $data->save();
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "Quan ly folder",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa tạo thành viên '.$uuidUser
                ]);
            }
            return response()->json(false,404);
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
