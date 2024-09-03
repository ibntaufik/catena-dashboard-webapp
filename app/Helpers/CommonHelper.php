<?php

namespace App\Helpers;

use DateTime;
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
            $result = explode(':', $key);
            Cache::forget($result[1]);
        }
    }

    public static function isValidDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        // Check if date was successfully parsed and is not just any valid date
        return $d && $d->format($format) === $date;
    }

}

?>