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
            $rowEvc = explode("-", $row[0]);

            $isExist = VCH::findByCode($rowEvc[1]);
            
            if($isExist) continue;

            $result = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where([
                "provinces.name" => $row[1],
                "cities.name" => $row[2],
                "districts.name" => $row[3],
                "sub_districts.name" => $row[4]
            ])->select(DB::raw("provinces.name AS province, cities.name AS city, districts.id, districts.name AS district, sub_districts.name AS sub_district, sub_districts.id"))->first();
            if($result){
                
                $evc = Evc::findByCode($rowEvc[0]);

                if(!empty($evc)){
                    $user = VCH::create([
                        "code"   => $rowEvc[1],
                        "evc_id" => $evc->id,
                        "sub_district_id" => $result->id,
                        "latitude"  => $row[5],
                        "longitude" => $row[6],
                        "address"   => $row[7],
                    ]);
                }
            }
        }
    }
}
