<?php

namespace App\Excel\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\District;

class Farmer implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = json_decode($row, true);
            $prefix = District::join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->join("sub_districts", "sub_districts.district_id", "districts.id")
                ->where([
                    "provinces.name" => $row[4],
                    "cities.name" => $row[5],
                    "districts.name" => $row[6],
                    "sub_districts.name" => $row[7]
            ])->select(DB::raw("provinces.name AS province, cities.name AS city, districts.name AS district, sub_districts.id, sub_districts.name AS kelurahan, sub_districts.code"))->first();
            if($prefix){
                \Log::debug($prefix);
                $farmer = \App\Model\Farmer::withTrashed()->where("sub_district_id", $prefix["id"])
                    ->orderBy("code", "DESC")->select("code")->first();

                if(empty($farmer)){
                    $code = $prefix->code.str_pad(1, 5, "0", STR_PAD_LEFT);
                } else {
                    $code = $prefix->code.str_pad((substr($farmer->code, 12) + 1), 5, "0", STR_PAD_LEFT);
                }

                $count = \App\Model\User::count();
                $email = "farmer$count@gmail.com";
                

                $dataUser = [
                    "email" => $email,
                    "password" => Hash::make("password"),
                    "name" => $row[0],
                    "phone" => "",
                    "created_by" => "System Administrator"
                ];

                $user = \App\Model\User::create($dataUser);

                $dataFarmer = [
                    "user_id"           => $user->id,
                    "code"              => $code,
                    "sub_district_id"   => $prefix["id"],
                    "id_number"         => $row[3],
                    "latitude"          => $row[8],
                    "longitude"         => $row[9],
                    "address"           => $row[10],
                    "created_by"        => "System Administrator"
                ];
                \Log::debug($dataFarmer);
                \App\Model\Farmer::create($dataFarmer);
                
            } else {
                //\Log::debug($row);
            }
        }
    }
}
