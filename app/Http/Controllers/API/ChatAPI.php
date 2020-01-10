<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Chat;
use Illuminate\Support\Facades\DB;


class ChatAPI extends Controller
{
    private $roomId;
    private function user($token)
    {
      $key = "KEYS";
      $decoded = JWT::decode($token, $key, array('HS256'));
      return $decoded->user->id;
    }

    public function join()
    {

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
}
