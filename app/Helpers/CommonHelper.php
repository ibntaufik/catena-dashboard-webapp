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

    public static function validateImage($file){

        $result = [
            "is_valid"  => false,
            "message"   => "",
            "file_type" => ""
        ];

        try{
            $data = explode(',', $file);
            $fileType = explode(';', $data[0]);
            $decode = base64_decode($data[1]);
            $isImage = explode(':', $fileType[0]);

            if(strpos($isImage['1'], 'image') === false){
                $result["message"] = 'ID photo must be an image';
            } else if(!in_array($isImage['1'], ["image/png", "image/jpeg", "image/jpg"])){
                $result["message"] = "Photo's extention must be .png, .jpeg, or .jpg";
            } else if (!$decode){
                $result["message"] = 'ID photo is not valid';
            } else {
                $result["is_valid"] = true;
                $result["file_type"] = str_replace("image/", ".", $isImage['1']);
            }
        } catch (\Exception $e){
            $result["message"] = 'Failed to validating image file';
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return $result;
    }

}

?>