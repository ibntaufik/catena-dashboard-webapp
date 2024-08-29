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
use App\Model\VCP;

class VCPS implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $arrayRow = explode("-", $row[0]);

            $isExist = VCP::findByCode($arrayRow[2]);
            
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
                
                $vch = Evc::join("t_vch", "t_vch.evc_id", "t_evc.id")->where([
                    "t_evc.code" => $arrayRow[0],
                    "t_vch.code" => $arrayRow[1],
                ])->select(DB::raw("t_vch.id"))->first();

                if(!empty($vch)){
                    VCP::create([
                        "vch_id"    => $vch->id,
                        "code"      => $arrayRow[2],
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
