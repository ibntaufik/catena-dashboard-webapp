<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CommonHelper
{
    
    public static function forgetWildcard($pattern, int $maxCountValue = 10000)
    {
        
        $redis = Cache::getRedis();
        $currentCursor = '0';
        do {
            $response = $redis->scan($currentCursor, 'MATCH', $pattern, 'COUNT', $maxCountValue);
            
            $currentCursor = $response[0];
            $keys = $response[1];
            if (count($keys) > 0) {
                // remove all found keys
                $redis->del($keys);
            }
        } while ($currentCursor !== '0'); // finish if current Cursor is reaching '0'        
    }

}

?>