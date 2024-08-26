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
use App\Model\Subdistrict;

class Subdistricts implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = json_decode($row, true);
            $result = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where([
                "provinces.name" => $row[0],
                "cities.name" => $row[1],
                "districts.name" => $row[2],
                "sub_districts.name" => $row[3]
            ])->select(DB::raw("provinces.name AS province, cities.name AS city, districts.id, districts.name AS district, sub_districts.name AS sub_district"))->first();
            if($result){

                /*District::where("districts.id",$result->id)->update([
                    "code" => $row[3],
                    "name" => strtoupper($result->name)
                ]);*/
            } else {
                $province = Province::where("name", $row[0])->first();
                if($province){\Log::debug($province);
                    $city = City::where([
                        "name"  => $row[1],
                        "province_id"   => $province->id
                    ])->select("cities.id")->first();

                    if($city){\Log::debug($city);
                        $district = District::where([
                            "city_id" => $city->id,
                            "name"  => strtoupper($row[2])
                        ])->select("districts.id")->first();

                        if($district){\Log::debug($district);
                            Subdistrict::create([
                                "code" => $row[6],
                                "name" => strtoupper($row[3]),
                                "district_id" => $district->id,
                                "latitude" => $row[4],
                                "longitude" => $row[5],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
