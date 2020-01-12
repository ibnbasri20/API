<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Event;
use App\Comment;
use App\Resources\EventResource;
use \Firebase\JWT\JWT;
use Storage;
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
        $event = Event::with(['publisher', 'publisher_info'])->orderBy('created_at', 'DESC')->paginate(15);
        if(request()->search != ''){
            $event = $event->where('name', 'like', '%' . request()->search . '%');
        }
        return response($event);
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if(! is_null(request()->file('images'))){
            $files = $request->file('images');
            $file_count = count($files);
            $uploadcount = 0;
            $nama = array();
            foreach($files as $file) {
                $destinationPath = 'uploads';
                $filename = $file->getClientOriginalName();
                $upload_success = $file->move($destinationPath, $filename);
                $nama[] = date('Y-m-d-H:i:s')."-".$file->getClientOriginalName();
                $uploadcount ++;
            }
            $data = Event::firstOrCreate([
                'id'        => rand(),
                'name'      => $request->name,
                'category'  => $request->category,
                'photo'     => implode(",", $nama),
                'publisher' => $this->user($request->header('Authorization')),
                'start'     => $request->start,
                'end'       => $request->end
            ]);
            if(!$data){
                return response('Harap isi');
            }
            return response("Berhasil");
        }
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
        $cek = Event::where('id', $id)->get();
        $comment = Comment::where('event_id', $id)->paginate(10);
        if(!$cek) return response()->json(["msg" => "Event tidak ditemukan"]);
        return response()->json(["event" => $cek, "comment" => $comment]);
    }


/*    public function comment($id)
    {
        $event = Event::where('id', $id)->first();
        if(!$event) return response()->json(["msg" => "Event tidak ditemukan"]);
        $comm = Comment::where('id_event', $id);
        return response($comm->first());
    }*/
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
