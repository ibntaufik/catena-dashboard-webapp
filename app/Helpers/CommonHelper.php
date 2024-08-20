<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CommonHelper
{
    
    public static function forgetCache($pattern, int $maxCountValue = 10000)
    {
        $keys = Redis::connection('cache')->keys("*$pattern*");
        foreach($keys as $key){
            $result = explode(':', $key);\Log::debug($result);
            Cache::forget($result[1]);
        }
    }

}

?>