<?php

namespace App\Excel\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\Farms;
use App\Model\Subdistrict;
use App\Model\Coffee;
use App\Model\CoffeeVariety;
use App\Model\CoffeeFarmDetail;
use App\Model\CoffeeVarietyFarmDetail;
use App\Model\FarmDetail;
use App\Model\Supply;
use App\Model\VCH;
use App\Model\VCP;

class Farmers implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        $notFound = [];

        foreach ($rows as $row) {
            $supply = Supply::where("record_id", $row[0])->first();\Log::debug($supply);
            if(!empty($supply)){
                $farm = Farms::create([
                    "farm_id"           => $row[2],
                    "elevation"         => $row[3],
                    "land_measurement"  => $row[4],
                    "supply_id"         => $supply->id
                ]);
                
                $farmDetail = FarmDetail::create([
                    "farm_id"           => $farm->id,
                    "tree_population"   => (!empty($row[6]) && is_numeric($row[6])) ? $row[6] : null,
                    "land_status"       => null,
                    "farm_photo"        => null
                ]);

                $coffee = Coffee::where("name", $row[1])->first();
                if(empty($coffee)){
                    $coffee = Coffee::create(["name" => $row[1]]);
                }

                $detail = CoffeeFarmDetail::create([
                    "coffee_id"         => $coffee->id,
                    "farm_detail_id"    => $farmDetail->id
                ]);

                if(!empty($row[5])){
                    $coffeeVariety = explode(",", $row[5]);

                    foreach($coffeeVariety as $variety){
                        $cVariety = CoffeeVariety::where(["name" => ucfirst($variety)])->first();
                        if(empty($cVariety)){
                            $cVariety = CoffeeVariety::create([
                                "name"  => ucfirst($variety)
                            ]);
                        } 

                        CoffeeVarietyFarmDetail::create([
                            "coffee_variety_id" => $cVariety->id,
                            "farm_detail_id"    => $detail->id
                        ]);
                    }
                }
            }
        }

        if(count($notFound) > 0){
            \Log::debug($notFound);
        }
    }
}
