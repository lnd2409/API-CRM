<?php

namespace App\Http\Controllers;

use App\UserCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\Auth;

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
        // if ($request->has('api_token')) {
            # code...
            // $user = UserModel::where('USER_TOKEN', $request->get('api_token'))->first();
            // if ($user) {
                # code...
                $data = $request->all();
                if ($request->has('AVATA')) {
                    # code...
                    $file = $request->file('AVATA');
                    $fileName = $file->getClientOriginalName();
                    $file->move('upload/avata',$fileName);
                    $path = 'upload/avata/'.$fileName;
                    $data['AVATA'] = $path;
                }
                $data['PASSWORD'] = md5($request->get('PASSWORD'));
                $user_new = UserCRM::create($data);
            // }
        // }
        // return response()->json('error');
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

    public function loginUser(Request $request)
    {
        $user = UserCRM::where('USERNAME',$request->get('USERNAME'))->first();
        if($user)
        {
            if(md5(($request->get("PASSWORD")), $user['PASSWORD']))
            {
                $token = JWTAuth::fromUser($user);
                $user = UserCRM::where('USERNAME',$request->get("USERNAME"))->update([
                    "USER_TOKEN" => $token
                ]);
                return response()->json($token, 200);
            }
            else {
                return response()->json(false, 404);
            }
        }
        else {
            return response()->json('error', 404);
        }
    }

    public function logoutUser(Request $request)
    {
        $token = $request->get('api_token');
        
        $user= UserCRM::where('USER_TOKEN',$token)->update([
            'USER_TOKEN' => null
        ]);
        return response()->json($user, 200);
    }
}
