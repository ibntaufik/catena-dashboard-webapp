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

class EVCS implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $evc = Evc::findByCode($row[0]);

            if(empty($evc)){
                $result = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where("sub_districts.name", $row[4])
                ->where("districts.name", $row[3])
                ->where("cities.name", $row[2])
                ->where("provinces.name", $row[1])
                ->select("sub_districts.id")
                ->first();
                if(!empty($result)){
                    Evc::create([
                        "code"   => $row[0],
                        "sub_district_id" => $result->id,
                        "address"   => null,
                        "latitude"  => $row[5],
                        "longitude" => $row[6],
                    ]);
                }
            }
        }
    }
}
