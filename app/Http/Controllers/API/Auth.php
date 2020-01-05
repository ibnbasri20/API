<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use \Firebase\JWT\JWT;

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

    public function store(Request $request)
    {
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if($check) return response()->json(["msg" => "Email / Username Telah DiPakai"]);
        $data = array(
            'id'            => Str::uuid(),
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'name'          => $request->name
        );
        User::create($data);
        return response()->json(["msg" => "Berhasil Daftar", "email" => "Harap cek email"]);
    }

    public function request_reset_password(Request $request)
    {
        # code...
    }

    public function login(Request $request)
    {
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if(!$check) return response()->json(["msg" => "Data Tidak Ditemukan"]);
        if(!Hash::check($request->password, $check->password)) return response()->json(["msg" => "Passowrd Salah"]);
        return $this->token($check);
    }
}
