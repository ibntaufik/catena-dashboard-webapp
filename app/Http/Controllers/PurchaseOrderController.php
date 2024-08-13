<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\VCH;

class PurchaseOrderController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $vch = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(VCH::listCombo(), true));

        return view("transactions.purchase-order.create");
    }
}
