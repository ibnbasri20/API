<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Comment;
use App\Resources\EventResource;
use \Firebase\JWT\JWT;
use App\Events\EventListener;
use App\Events;
use App\EventGroup;
use App\Groups;
use Illuminate\Support\Facades\DB;
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
        $event = Events::with(['publisher', 'publisher_info'])->orderBy('created_at', 'DESC')->paginate(15);
        if(request()->search != ''){
            $event = $event->where('name', 'like', '%' . request()->search . '%');
        }
        return response($event);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'location'  => 'required',
//            'lat'       => 'required',
//            'long'      => 'required',
            'start'     => 'required',
            'end'       => 'required',
            'images'    => 'required',
            'images.*'  => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
            
            $events = array(
                'id'        => rand(),
                'name'      => $request->name,
                'category'  => $request->category,
                'photo'     => implode(",", $nama),
                'lat'       => $request->lat,
                'long'      => $request->long,
                'location'  => $request->location,
                'publisher' => $this->user($request->header('Authorization')),
                'start'     => $request->start,
                'end'       => $request->end
            );
            $group = Groups::create([
                'name' =>  $request->name
            ]);
            \App\Events::create([
                'id'        => rand(),
                'name'      => $request->name,
                'category'  => $request->category,
                'photo'     => implode(",", $nama),
                'lat'       => $request->lat,
                'long'      => $request->long,
                'location'  => $request->location,
                'publisher' => $this->user($request->header('Authorization')),
                'start'     => $request->start,
                'end'       => $request->end,
                'group_id'  => $group->id
            ]);
            EventGroup::create([
                'id'            => rand(),
                'group_id'      => $group->id,
                'users_id'      => $this->user($request->header('Authorization')),
                'admin'         => 1
            ]);
            event(new EventListener($events));
            return response(["msg" => "Berhasil Menambahkan Data", "data" => $group->id]);
        }
    }

    public function join(Request $request, $id)
    {
      $cek = Events::where('id', $id)->first();
      if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
      $data = array(
          'id'            => rand(),
          'id_users'      => $this->user($request->header('Authorization')),
          'id_event'      => $id
      );
      $c_join = DB::table('event_join')->where(function($query) use ($id,$request) {
        $query->where('id_users', $this->user($request->header('Authorization')))
              ->where('id_event',$id);
      })->first();

      if($c_join) return response()->json(["msg" => " Ada Sudah Mengikuti"]);
      $join  = DB::table('event_join')->insert($data);
        EventGroup::create([
            'id'            => rand(),
            'group_id'      => $cek->group_id,
            'users_id'      => $this->user($request->header('Authorization')),
            'admin'         => 0
        ]);
      if(!$join) return response()->json(["msg" => "Gagal Join"]);
      return response(["msg" => "Ada Akan Mengikuti Event ini"]);
    }

    public function delete(Request $request, $id)
    {
        $cek = Events::where('id', $id);
        if(!$cek->first()) return response()->json(["msg" => "Event tidak ditemukan"]);
        $cek->delete();
        return response()->json(["msg" => "Event Berhasil DiHapus"]);
    }

    public function show($id)
    {
        $cek = Events::where('id', $id)->get();
        $comment = Comment::where('event_id', $id)->paginate(10);
        if(!$cek) return response()->json(["msg" => "Event tidak ditemukan"]);
        return response()->json(["event" => $cek, "comment" => $comment]);
    }

    public function send_comment(Request $request,$id)
    {
        $cek = Events::where('id', $id)->first();
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
        $cek = Events::where('id', $id)->first();
        if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
        $cek_comment = Comment::where('id', $id);
        if(!$cek_comment->first()) return response()->json(["msg" => "Komen tidak ditemukan"]);
        return $cek_comment->delete();
        return response()->json(["msg" => "Berhasi Menghapus Data"]);
    }
}
