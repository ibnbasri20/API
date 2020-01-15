<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use \Firebase\JWT\JWT;
use App\Events\UserListener;

class Auth extends Controller
{
    private function token($data){
        $key = "KEYS";
        $payload = array(
            "iss" => "http://your.org",
            "aud" => "http://your.com",
            "iat" => time(),
            "nbf" => time(),
            "user" => $data
        );
        $jwt = JWT::encode($payload, $key);
        return response()->json(["token" => $jwt]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'      => 'required|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'name'          => 'required' 
        ]);
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if($check) return response()->json(["msg" => "Email / Username Telah DiPakai"]);
        $data = array(
            'id'            => rand(),
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'name'          => $request->name
        );
        User::create($data);
        return response()->json(["msg" => "Berhasil Daftar"]);
    }

    public function request_reset_password(Request $request)
    {

    }

    public function login(Request $request)
    {
        $request->validate([
            'username'      => 'required',
            'password'      => 'required',
        ]);
        $check = User::where('username', $request->username)->orWhere('email', $request->email)->first();
        if(!$check) return response()->json(["msg" => "Data Tidak Ditemukan"]);
        if(!Hash::check($request->password, $check->password)) return response()->json(["msg" => "Passowrd Salah"]);
        event(new UserListener('Berhasl Login'));
        return $this->token($check);
    }
}
