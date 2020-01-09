<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserAPI extends Controller
{
    public function show(Request $request, $id)
    {
      $cek = User::where('id',$id);
      if(!$cek->first()) return response()->json(["Data Tidak Ditemukan"]);
      return response()->json(["data" => $cek->first()]);
    }

    public function update_profile(Request $request)
    {
      $cek = User::where('id', $requst->id);
      if($cek->first()) return response()->json(["msg" => "Data tidak ditemukan"]);
      $cek->update([
        $request->all()
      ]);
    }

    public function change_password(Request $request)
    {
      $cek = User::where('id', $request->id);
      if(!$cek->first()) return response()->json(["msg" => "Data tidak ditemukan"]);
      $data = $request->validate([
        'password_old'  => 'required',
        'new_password'  => 'required',
        'new_password2'  => 'required'
      ]);
      if(!Hash::check($request->password, $cek->first()->password)) return response()->json(["msg" => "Password Salah"], 422);
      if($request->new_password !== $request->new_password) return response()->json(["msg" => "Tidak sama"], 422);
      $cek->update([
        "passsword" => Hash::make($request->new_password2);
      ]);
      return response()->json(["Berhasil Mengupdate Password"]);
    }

    public function request_delete_user(Request $request , $id)
    {

    }
}
