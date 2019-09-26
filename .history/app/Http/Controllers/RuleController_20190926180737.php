<?php

namespace App\Http\Controllers;

use App\Rule;
use App\UserCRM;
use Illuminate\Http\Request;

class RuleController extends Controller
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
            $user = UserCRM::where([
                ["USER_TOKEN",$request->get('api_token')],
                ["UUID_RULE", "coder-2019"]])->first();
            if($user)
            {
                $rule = Rule::all();
                return response()->json($rule,200);
            }
            return response()->json(false, 404);
        }
        return response()->json(false, 200);
        
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rule = Rule::where('UUID_RULE',$id)->first();
        if ($rule) {
            # code...
            return response()->json($rule,200);
        }else{
            $error = 'Không tìm thấy!';
            return response()->json($error);
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
        $rule = Rule::where('UUID_RULE',$id)->update([
            "NAME_RULE" => $request->NAME_RULE,
            "NOTE_RULE" => $request->NOTE_RULE
        ]);
        return response()->json($rule,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rule = DB::table('crm_rule')->where('UUID_RULE',$id)->first();
        return response([
            'error' => false,
            'msg' => 'Da xoa!'
        ]);
    }
}
