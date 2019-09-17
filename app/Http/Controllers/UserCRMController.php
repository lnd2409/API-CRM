<?php

namespace App\Http\Controllers;

use App\UserCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCRMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = UserCRM::all();
        return response()->json($user, 200);
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
        $user = new UserCRM();
        $user->UUID_USER = $request->get('idUser');
        $user->UUID_RULE = $request->get('idRule');
        $user->USERNAME = $request->get('username');
        $user->PASSWORD = md5($request->get('password'));
        $user->NAME = $request->get('name');
        $user->PHONE = $request->get('phone');
        $user->GENDER = $request->get('gender');
        $user->BIRTH_DAY = $request->get('birthday');
        $user->ADDRESS = $request->get('address');
        $user->USER_TOKEN = $request->get('userToken');
        $user->NOTIFY_TOKEN = $request->get('notiftToken');
        $getAvata = $request->file('avata');
        if ($getAvata) {
            # code...
            $getNameAvata = $getAvata->getClientOriginalName();
            $getAvata->move('upload/avata',$getNameAvata);
            $user->AVATAR = $getNameAvata;
            $user->save();
            return response()->json($user, 200);
        }
        else
        {
            $user->save();
            return response()->json($user, 200);
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
        $user = UserCRM::where('UUID_USER',$id)->first();
        if ($user) {
            # code...
            return response()->json($user, 200);
        }else{
            return response([
                'error' => true,
                'msg' => 'Khong tim thay user'
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
        $user = DB::table('crm_user')->where('UUID_USER','=',$id)->delete();
        return response()->json($user, 200);
    }
}
