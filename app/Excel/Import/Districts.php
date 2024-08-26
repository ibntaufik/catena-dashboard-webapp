<?php

namespace App\Excel\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\Province;
use App\Model\City;
use App\Model\District;

class Districts implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = json_decode($row, true);
            $result = District::join("cities", "cities.id", "districts.city_id")->join("provinces", "provinces.id", "cities.province_id")->where([
                "provinces.name" => $row[0],
                "cities.name" => $row[1],
                "districts.name" => $row[2]
            ])->select(DB::raw("provinces.name AS province, cities.name AS city, districts.id, districts.name"))->first();
            if($result){
                District::where("districts.id",$result->id)->update([
                    "code" => $row[3],
                    "name" => strtoupper($result->name)
                ]);
            } else {
                $province = Province::where("name", $row[0])->first();
                if($province){
                    $city = City::where([
                        "name"  => $row[1],
                        "province_id"   => $province->id
                    ])->select("cities.id")->first();

                    if($city){
                        District::create([
                            "city_id" => $city->id,
                            "name"  => strtoupper($row[2]),
                            "code"  => $row[3]
                        ]);
                    }
                }
            }
        }
    }
}
