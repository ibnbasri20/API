<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Facades\Support\Hash;

class Auth extends Controller
{
    private function token($data){
        $key = "KEYS";
        $payload = array(
            "iss" => "http://your.org",
            "aud" => "http://your.com",
            "iat" => time(),
            "nbf" => time()
        );
        $jwt = JWT::encode($payload, $key);        
        return response()->json(["token" => $jwt]);
    }

    public function store(Type $var = null)
    {
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if($check) return response()->json(["msg" => "Email / Username Telah DiPakai"]);

    }

    public function login(Type $var = null)
    {
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if(!$check) return response()->json(["msg" => "Data Tidak Ditemukan"]);
        if(!Hash::check($request->password, $check->password)) return response()->json(["msg" => "Passowrd Salah"]);
        return $this->token($check);
    }

}
