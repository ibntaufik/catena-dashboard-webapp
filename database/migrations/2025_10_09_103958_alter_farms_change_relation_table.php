<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableFarm = "farms";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->dropForeign($this->tableFarm.'_supply_id_foreign');
            $table->dropColumn(['supply_id']);

            $table->unsignedInteger('supplier_id')->nullable()->index()->after("id");
            $table->foreign('supplier_id')->references('id')->on("master_supplier");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->dropForeign($this->tableFarm.'_supplier_id_foreign');
            $table->dropColumn(['supplier_id']);
        });
    }
};
