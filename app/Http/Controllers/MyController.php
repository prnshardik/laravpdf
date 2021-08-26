<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request; 
use Auth, Validator, DB, Mail, Str;


class MyController extends BaseController
{
    public function index(Request $request){
        
        $data = [
            'id' => $request->id, 
            'content' => $request->text, 
        ];
        $save = DB::table('data')->insertGetId($data);
        return response()->json(['code' => 200]);
    }
    
    public function get_data(Request $request){
        $data = DB::table('data')->get();
        return view('get_data')->with(['data' => $data]);
    }
}
