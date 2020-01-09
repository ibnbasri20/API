<?php

namespace App\Http\Middleware;
use Closure;
use \Firebase\JWT\JWT;
class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      try{
        $key = "KEYS";
        $header = $request->header('Authorization');
        $decoded = JWT::decode($header, $key, array('HS256'));
      }catch(Exception $e){
        return response($e);
      }

        return $next($request);
    }
}
