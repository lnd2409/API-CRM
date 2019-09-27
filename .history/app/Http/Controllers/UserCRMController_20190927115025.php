<?php

namespace App\Http\Controllers;

use App\History;
use App\UserCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\Auth;

class UserCRMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user)
            {
                if ($request->has('search')) {
                    # code...
                    $user = UserCRM::where('USERNAME','like','%'.$request->get('search').'%')->select('UUID_USER','AVATAR','USERNAME')->get();
                    return response()->json($user,200);
                }
                else if($request->has('get_data'))
                {
                    return response()->json($user, 200);
                }
                else{
                    $user = UserCRM::all();
                    return response()->json($user, 200);
                }
            }
        }        
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
        if ($request->has('api_token')) {
            # code...
            $user = UserCRM::where('USER_TOKEN', $request->get('api_token'))->first();
            if ($user) {
                # code...
                $data = $request->all();
                $data["AVATAR"] = null;
                if ($request->has('AVATAR')) {
                    # code...
                    $file = $request->file('AVATAR');
                    $fileName = $file->getClientOriginalName();
                    $file->move('upload/avatar',$fileName);
                    $path = 'upload/avatar/'.$fileName;
                    $data['AVATAR'] = $path;
                }
                $data['PASSWORD'] = md5($request->get('PASSWORD'));
                $user_new = UserCRM::create([
                    "UUID_USER" => Str::uuid(),
                    "UUID_RULE" => 'coder-2019',
                    "AVATAR" => $data["AVATAR"],
                    "USERNAME" => $data["USERNAME"],
                    "PASSWORD" => $data["PASSWORD"],
                    "NAME" => $data["NAME"],
                    "EMAIL" => $data["EMAIL"],
                    "PHONE" => $data["PHONE"],
                    "GENDER" => $data["GENDER"],
                    "BIRTH_DAY" => $data["BIRTH_DAY"],
                    "ADDRESS" => $data["ADDRESS"],
                ]);
                if($user_new)
                {
                    History::create([
                        "UUID_HISTORY" => Str::uuid(),
                        "UUID_USER" => $user->UUID_USER,
                        "NAME_HISTORY" => "user",
                        "NOTE_HISTORY" => $user->USERNAME.' tạo người dùng '.$data["USERNAME"]
                    ]);
                    return response()->json($user_new, 200);
                }
                return response()->json(false, 400);
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
    public function show($id, Request $request)
    {
        if($request->has('api_token'))
        {
            $user_token = UserCRM::where("USER_TOKEN",$request->get("api_token"))->first();
            if($user_token)
            {
                $user = UserCRM::where('USERNAME',$id)->first();
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
        if($request->has('api_token'))
        {
            $user = UserCRM::where("USER_TOKEN",$request->get("api_token"))->first();
            if($user)
            {
                $data = $request->all();
                
                $user_update = UserCRM::where("UUID_USER",$id)->update([
                    "UUID_RULE" => $data["UUID_RULE"],
                    "NAME" => $data["NAME"],
                    "EMAIL" => $data["EMAIL"],
                    "PHONE" => $data["PHONE"],
                    "GENDER" => $data["GENDER"],
                    "BIRTH_DAY" => $data["BIRTH_DAY"],
                    "ADDRESS" => $data["ADDRESS"],
                ]);
                if($user_update)
                {
                    if ($request->has('AVATAR')) {
                        # code...
                        $file = $request->file('AVATAR');
                        $fileName = $file->getClientOriginalName();
                        $file->move('upload/avatar',$fileName);
                        $path = 'upload/avatar/'.$fileName;
                        $data['AVATAR'] = $path;
                        UserCRM::where("UUID_USER",$id)->update([
                            'AVATAR' => $data["AVATAR"]
                        ]);
                        History::create([
                            "UUID_HISTORY" => Str::uuid(),
                            "UUID_USER" => $user->UUID_USER,
                            "NAME_HISTORY" => "user",
                            "NOTE_HISTORY" => $user->USERNAME.' cập nhật user '.$data["USERNAME"]
                        ]);
                        return response()->json($user_update, 200);
                    }
                }
                return response()->json($user_update    , 400);
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
        
        $user = UserCRM::where('USER_TOKEN',$token)->update([
            'USER_TOKEN' => null
        ]);
        return response()->json('Đã đăng xuất', 200);
    }

    public function checkUsername(Request $request){
        $user = $request->get('USERNAME');
        $getAllUser = UserCRM::where('USERNAME',$user)->first();
        if ($getAllUser) {
            # code...
           return response()->json(false, 200);
        }else{
            // $token = $request->get('api_token');
           return response()->json(true, 200);
        }
    }

    public function history()
    {
        $historys = History::all();
        return response()->json($historys, 200);
    }
}
