<?php

namespace App\Http\Middleware;

use Closure;
use Common;
use App\EmployeeV2;
use App\Helpers\CommonHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class APIAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if($request->headers->get('api-key')){
            if($request->headers->get('api-key') !== env("API_KEY")){
                return response()->json(['response' => ['code' => 400,'message' =>'Invalid API KEY.'], 'data' => ''], 401);
            } 
            if(in_array($type, ["auth","mobile"])){
                if($type == "mobile"){
                    $auth = $request->header('Authorization');
                    if (!$auth || !preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
                        return response()->json(['response' => ['code' => 400,'message' =>'Token is required.'], 'data' => ''], 401);
                    }
                    $token = $matches[1];

                    try {
                        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                        // $decoded->sub is user_id
                    } catch (\Exception $e) {
                        return response()->json(['error' => 'Invalid token'], 401);
                    }
                }
            } else{
                return response()->json(['response' => ['code' => 400,'message' =>'You have no authorization to access this portal.'], 'data' => ''], 401);
            }
        } else {
            return response()->json(['response' => ['code' => 400,'message' =>'API Key is required'], 'data' => ''], 401);
        }

        return $next($request);
    }
}
