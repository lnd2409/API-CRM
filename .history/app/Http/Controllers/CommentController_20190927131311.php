<?php

namespace App\Http\Controllers;

use App\Comment;
use App\UserCRM;
use App\History;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cmt = Comment::all();
        return response()->json($cmt,200);
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
        if($request->has('api_token'))
        {
            $user = UserCRM::where("USER_TOEKN",$request->get('api_token'))->first();
            if($user)
            {
                $cmt = Comment::create([
                    'UUID_COMMENT' => Str::uuid(),
                    'UUID_FILE_MANAGEMENT' => $request->get("UUID_FILE_MANAGEMENT"),
                    'UUID_USER' => $user->UUID_USER,
                    'CONTENT_COMMENT' => $request->get('CONTENT_COMMENT'),
                    'NAME_USER' => $user->USERNAME,
                ]);
                if($cmt)
                {
                    History::create([
                        "UUID_HISTORY" => Str::uuid(),
                        "UUID_USER" => $user->UUID_USER,
                        "NAME_HISTORY" => 'Comment',
                        "NOTE_HISTORY" => $user->USERNAME.' comment '.$request->get("CONTENT_COMMENT")
                    ]);
                    return response()->json($cmt, 200);
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
    public function show(Request $request,$id)
    {
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $checkCmt = Comment::where('NAME_USER',$user->USERNAME)->first();
                if($checkCmt){
                    $cmt = Comment::where('UUID_COMMENT',$id)->where('NAME_USER',$user->USERNAME)->first();
                    return response()->json($cmt,200);
                }
            }
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
        if($request->get('api_token'))
        {
            $user = UserCRM::where('USER_TOKEN',$request->get('api_token'))->first();
            if($user){
                $cmt = Comment::where('UUID_COMMENT',$id)->update([
                    'CONTENT_COMMENT' => $request->CONTENT_COMMENT
                ]);
                History::create([
                    "UUID_USER" => $user->UUID_USER,
                    "UUID_HISTORY" => Str::uuid(),
                    "NAME_HISTORY" => "user",
                    "NOTE_HISTORY" => $user->USERNAME.' vừa cập nhật bình luận '.$cmt->UUID_FILE_MANAGEMENT
                ]);
                return response()->json($cmt,200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cmt = Comment::where('UUID_COMMENT',$id)->delete();
        return response([
            'msg' => 'Xoa thanh cong'
        ],200);
    }
}
