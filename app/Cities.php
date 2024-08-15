<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\Province;
use App\Model\City;

class Cities implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = json_decode($row, true);
            if($row[1]){
                $result = City::join("provinces", "provinces.id", "cities.province_id")->where([
                    "cities.name" => $row[1],
                    "provinces.name" => $row[0]
                ])->select(DB::raw("provinces.name AS province_name, cities.*"))->first();
                if($result){
                    City::where("id", $result->id)->update([
                        "code" => strtoupper($row[2]),
                        "name" => strtoupper($result->name),
                    ]);
                } else {
                    $province = Province::where("name", $row[0])->first();
                    City::create([
                        "name" => strtoupper($row[1]),
                        "code" => strtoupper($row[2]),
                        "province_id" => $province->id
                    ]);
                }
            }
        }
    }
}
