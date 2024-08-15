<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\Province;

class Provinces implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $row = json_decode($row, true);
            $result = Province::where([
                "provinces.name" => $row[0]
            ])->select(DB::raw("provinces.id, provinces.name"))->first();
            if($result){
                Province::where("id", $result->id)->update(["code" => $row[1], "name" => strtoupper($result->name)]);
            } else {
                Province::create([
                    "name" => strtoupper($row[0]),
                    "code" => strtoupper($row[1]),
                ]);
            }
        }
    }
}
