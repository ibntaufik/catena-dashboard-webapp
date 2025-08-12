<?php

namespace App\Excel\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Model\Subdistrict;
use App\Model\SupplierCategory;
use App\Model\SupplyCategory;
use App\Model\Supplier;
use App\Model\SupplierDetail;
use App\Model\Supply;
use App\Model\VCH;
use App\Model\VCP;

class SupplierDetails implements ToCollection
{
    use HasFactory;

    public function collection(Collection $rows)
    {

        $notFound = [];
        foreach ($rows as $row) 
        {
            $subdistrict = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where([
                "provinces.name" => $row[3],
                "cities.name" => $row[4],
                "districts.name" => $row[5],
                "sub_districts.name" => $row[6]
            ])
            ->select(DB::raw("sub_districts.id"))
            ->first();

            if($subdistrict){
                
                $category = SupplyCategory::whereRaw("LOWER(code) = ?", strtolower($row[2]))->select("id")->first();
                
                $result = Supplier::where([
                    "name" => $row[0],
                    "sub_district_id" => $subdistrict->id,
                ])->first();

                if(empty($result)){
                    $supplier = Supplier::create([
                        "name" => $row[0],
                        "alias" => $row[1],
                        "sub_district_id" => $subdistrict->id,
                        "verification_status" => $row[8]
                    ]);

                    $vcp = null;
                    $vch = null;
                    if(!empty($row[12])){
                        $arrayRow = explode("-", $row[12]);
                        $vcp = VCP::join("t_vch", "t_vch.id", "t_vcp.vch_id")
                        ->where("t_vch.code", $row[11])
                        ->where("t_vcp.code", $arrayRow[1])
                        ->select("t_vcp.id")->first();
                    } else {
                        $vch = VCH::where("t_vch.code", $row[11])
                        ->select("t_vch.id")->first();
                    }

                    SupplierCategory::create([
                        "supplier_id"   => $supplier->id,
                        "category_id"   => $category->id
                    ]);

                    Supply::create([
                        "user_code"     => $row[9],
                        "vch_id"        => empty($vch) ? null : $vch->id,
                        "vcp_id"        => empty($vcp) ? null : $vcp->id,
                        "supplier_id"   => $supplier->id,
                        "latitude"      => $row[14],
                        "longitude"     => $row[15],
                        "altitude"      => $row[16],
                        "nid_generate"  => $row[13],
                        "record_id"     => $row[17]

                    ]);
                } else {
                    Supply::create([
                        "user_code" => $row[9],
                        "vch_id"        => empty($vch) ? null : $vch->id,
                        "vcp_id"        => empty($vcp) ? null : $vcp->id,
                        "supplier_id"   => $result->id,
                        "latitude"      => $row[14],
                        "longitude"     => $row[15],
                        "altitude"      => $row[16],
                        "nid_generate"  => $row[13],
                        "record_id"     => $row[17]

                    ]);

                    $supplierCategory = SupplierCategory::where([
                        "supplier_id"   => $result->id,
                        "category_id"   => $category->id
                    ])->first();

                    if(empty($supplierCategory)){
                        SupplierCategory::create([
                            "supplier_id"   => $result->id,
                            "category_id"   => $category->id
                        ]);
                    }
                }
            } else {
                $notFound[] = $row[7];
                \Log::debug("Supplier Name ".$row[0]);
            }
        }

        if(count($notFound) > 0){
            \Log::debug("Locality ID not found: ".implode(", ", $notFound));
        }
    }
}
