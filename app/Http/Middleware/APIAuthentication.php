<?php

namespace App\Http\Middleware;

use Closure;
use Common;
use App\EmployeeV2;
use App\Helpers\CommonHelper;

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
            if(!in_array($request->headers->get('api-key'), ["jwENENd4VyL1"])){
                return response()->json(['response' => ['code' => 400,'message' =>'Invalid API KEY.'], 'data' => '']);
            } 
        }else{
            /*
            if(!Common::getAPIAccess(request('api_key'), $type)){
                if(in_array($request->get('api_key'), ["jwENENd4VyL1"])){
                    return response()->json();
                }
                return response()->json(['response' => ['code' => 400,'message' =>'Invalid API key.'], 'data' => '']);
            }
            */
        }

        return $next($request);
    }
}
