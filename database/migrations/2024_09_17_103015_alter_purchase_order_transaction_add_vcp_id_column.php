<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "purchase_order_transaction";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->unsignedInteger('vcp_id')->nullable()->index()->after("account_farmer_id");
                $table->foreign('vcp_id')->references('id')->on('t_vcp');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropForeign($this->table.'_vcp_id_foreign');
            $table->dropColumn('vcp_id');
        });
    }
};
