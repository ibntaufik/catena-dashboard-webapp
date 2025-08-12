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

class Engagements implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        $notFound = [];

        foreach ($rows as $row) {
            $supply = Supply::where("record_id", $row[0])->first();
            \Log::debug($supply);
        }

        if(count($notFound) > 0){
            \Log::debug($notFound);
        }
    }
}
