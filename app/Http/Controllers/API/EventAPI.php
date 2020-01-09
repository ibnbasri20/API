<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Event;
use App\Comment;
use App\Resources\EventResource;
use \Firebase\JWT\JWT;
class EventAPI extends Controller
{
    private function user($token)
    {
      $key = "KEYS";
      $decoded = JWT::decode($token, $key, array('HS256'));
      return $decoded->user->id;
    }
    public function index()
    {
        $event = Event::with(['user'])->orderBy('created_at', 'DESC')->paginate(15);
        if(request()->search != ''){
            $event = $event->where('name', 'like', '%' . request()->search . '%');
        }
        return response($event);
    }

    public function store(Request $request)
    {

      $validate = $request->validate([
            'name'    => 'required|min:10',
            'start'   => 'required',
            'end'     => 'required',
            'images'  => 'required',
            'images.*' => 'required'
        ]);
        if(!$validate) return response($validate);
        $images = [];
        if($request->file('images')){
          foreach($request->file('images') as $file){
            $name=$file->getClientOriginalName();
            $file->move('image',$name);
            $images[]=$name;
          }
        }
         Event::create([
              'id'        => rand(),
              'name'      => $request->name,
              'category'  => $request->category,
              'photo'     => $images,
              'publisher' => $this->user($request->header('Authorization')),
              'start'     => $request->start,
              'end'       => $request->end
          ]);
    }

    public function join(Request $request, $id)
    {
      $cek = Event::where('id', $id)->first();
      if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
    }

    public function delete(Request $request, $id)
    {
        $cek = Event::where('id', $id);
        if(!$cek->fist()) return response()->json(["msg" => "Event tidak ditemukan"]);
        $cek->delete();
        return response()->json(["msg" => "Event Berhasil DiHapus"]);
    }

    public function show($id)
    {
        $cek = Event::where('id', $id);
        if(!$cek->fist()) return response()->json(["msg" => "Event tidak ditemukan"]);
        return response()->json(["event" => $cek , "comment" => $this->comment($id)]);
    }


    public function comment($id)
    {
        $event = Event::where('id', $id)->first();
        if(!$cek->fist()) return response()->json(["msg" => "Event tidak ditemukan"]);
        $comm = Comment::where('id_event', $id);
        return response($comm->paginate(10));
    }
    public function send_comment(Request $request,$id)
    {
        $cek = Event::where('id', $id)->first();
        if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
        Comment::create([
            'id_event'  => $id,
            'user_id'   => $request->user_id,
            'comment'   => $request->comment,
        ]);
        response()->json(["msg" => "Berhasil Mengomentari"]);
    }

    public function delete_comment(Request $request,$id)
    {
        $cek = Event::where('id', $id)->first();
        if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
        $cek_comment = Comment::where('id', $id);
        if(!$cek_comment->first()) return response()->json(["msg" => "Komen tidak ditemukan"]);
        return $cek_comment->delete();
        return response()->json(["msg" => "Berhasi Menghapus Data"]);
    }
}
