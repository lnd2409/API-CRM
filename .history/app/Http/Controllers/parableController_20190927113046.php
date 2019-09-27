<?php

namespace App\Http\Controllers;
use App\parable;
use App\UserCRM;
use App\History;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class parableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            $user = UserCRM::where("USER_TOKEN",$request->get('api_token'))->first();
            if($user)
            {
                $parable = parable::create([
                    "UUID_PARABLE" => Str::uuid(),
                    "CONTENT_PARABLE" => $request->get("CONTENT_PARABLE")
                ]);
                if($parable)
                {
                    Hitory::create([
                        "UUID_USER" => $user->UUID_USER,
                        "UUID_HISTORY" => Str::uuid(),
                        "NAME_HISTORY" => "Châm ngôn",
                        "CONTENT_HISTORY" => $user->USERNAME.' tạo châm ngôn '.$request->get("CONTENT_PARABLE")
                    ])
                }
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
