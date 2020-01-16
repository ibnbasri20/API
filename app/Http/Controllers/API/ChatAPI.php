<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Chat;
use Illuminate\Support\Facades\DB;
use App\GroupChat;

class ChatAPI extends Controller
{
    private $roomId;
    private function user($token)
    {
      $key = "KEYS";
      $decoded = JWT::decode($token, $key, array('HS256'));
      return $decoded->user->id;
    }

    public function getLatest(Request $request)
    {

        $lastMessages = Chat::where('sender_id', $this->user($request->header('Authorization')))->groupBy('received_id')->orderBy('received_id', 'desc')->orderBy('id', 'desc')->latest('created_at')->paginate(10);
        return $lastMessages;
    }

    public function sendchat(Request $request)
    {
        $user = $this->user($request->header('Authorization'));
        $users = new \App\User;
        $message = $users->messages()->create([
            'sender_id' => $user,
            'message' => $request->message,
            'received_id'  => $request->penerima
        ]);
        event(new MessageSent($message));
    }



    //group
    public function send_group_chat(Request $request)
    {
        $user = $this->user($request->header('Authorization'));
        $c_user = DB::table('chat_room')->where(function($query) use ($request) {
            $query->where('users_id', $this->user($request->header('Authorization')))
                  ->where('group_id',$request->group);
          })->first();
        if(!$c_user) return response()->json(["msg" => "Tidak Terdaftar"]);
        GroupChat::create([
            'id'        => rand(),
            'group_id'  => $c_user->group_id,
            'id_users'  => $user,
            'messages'  => $request->messages
        ]);
        return response()->json(["msg" => "Berhasil Mengirim Pesan"]);
    }

    public function get_latest_group(Request $request)
    {
        $lastMessages = GroupChat::where('id_users', $this->user($request->header('Authorization')))->with(['get_group','get_information_group'])->groupBy('group_id')->orderBy('group_id', 'desc')->orderBy('id', 'desc')->latest('created_at')->paginate(10);
        return response()->json(["data" => $lastMessages]);
    }

    public function get_latest_chat_group(Request $request, $id)
    {
        $lastMessages = GroupChat::where(function($query) use ($id,$request) {
            $query->where('id_users', $this->user($request->header('Authorization')))
                  ->where('group_id', $id);
          })->with(['get_group','get_information_group','get_user','user_info'])->latest('created_at')->paginate(10);
        return $lastMessages;
    }
}
