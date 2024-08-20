<?php

namespace App\Model;

use App\Model\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'item';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("item.list_combo", config("constant.ttl"), function(){
            $items = Item::select(DB::raw("id, name AS text"))->get()->toArray();

            foreach ($items as $key => $item) {
                $itemTypes = ItemType::where("item_id", $item["id"])->select(DB::raw("id, name AS text"))
                ->get()->toArray();

                $items[$key]["itemType"] =  array_merge([
                    ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
                ], $itemTypes);
            }

            return $items;
        });
    }
}
