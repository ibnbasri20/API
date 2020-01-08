<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Event;
use App\Comment;
use App\Resources\EventResource;
class EventAPI extends Controller
{
    public function index()
    {
        $event = Event::all();
        if(request()->search != ''){
            $event = $event->where('name', 'like', '%' . request()->search . '%');
        }
        return response(Event::paginate(15));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|min:10',
            'category' => 'required',
            'photo' => 'required',
            'start' => 'required',
            'end'   => 'required'
        ]);
        if(!$validate) return response($validate);
        Event::create([
            'id'        => rand(),
            'name'      => $request->name,
            'category'  => $request->category,
            'photo'     => $request->photo,
            'publisher' => $request->publisher,
            'start'     => $request->start,
            'end'       => $request->end
        ]);
        return response(["msg" => "Berhasil Membuat Event"]);
    }

    public function delete(Request $request, $id)
    {
        $cek = Event::where('id', $id);
        if(!$cek->fist()) return response()->json(["msg" => "Event tidak ditemukan"]);
        $cek->delete();
        return response()->json(["msg" => "Event Berhasil DiHapus"]);
    }


    public function comment($id)
    {
        $event = Event::where('id', $id)->first();
        if(!$cek->fist()) return response()->json(["msg" => "Event tidak ditemukan"]);
        $comm = Comment::where('id_event', $id);
        return response($com->paginate(10));
    }
    public function send_comment(Request $request,$id)
    {
        $cek = Event::where('id', $id)->first();
        if(!$cek) return response()->json(["msg" => "Data tidak ditemukan"]);
        Comment::create([
            'id_event'  => $id,
            'user_id'   => $request->user_id,
            'comment'   => $request->user_id,
        ]);
        response()->json(["msg" => "Berhasil Mengomentari"]);
    }   
}
