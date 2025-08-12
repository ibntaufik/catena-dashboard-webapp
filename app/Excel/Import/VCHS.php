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
use App\Model\Evc;
use App\Model\VCH;

class VCHS implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {

            $isExist = VCH::findByCode($row[1]);
            
            if($isExist) {

            } else {

                $result = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
                    ->join("cities", "cities.id", "districts.city_id")
                    ->join("provinces", "provinces.id", "cities.province_id")
                    ->where([
                    "provinces.name" => $row[2],
                    "cities.name" => $row[3],
                    "districts.name" => $row[4],
                    "sub_districts.name" => $row[5]
                ])->select(DB::raw("provinces.name AS province, cities.name AS city, districts.id, districts.name AS district, sub_districts.name AS sub_district, sub_districts.id"))
                ->first();
                
                if($result){
                    $evc = Evc::findByCode($row[0]);
                    if(!empty($evc)){
                        $user = VCH::create([
                            "code"   => $row[1],
                            "evc_id" => $evc->id,
                            "sub_district_id" => $result->id,
                            "address"   => $row[6],
                            "latitude"  => $row[7],
                            "longitude" => $row[8],
                            "altitude" => $row[9],
                            "status" => strtolower($row[10]),
                        ]);
                    }
                }
            };
        }
    }
}
