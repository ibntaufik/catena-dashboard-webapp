<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableProvince = "provinces";
    protected $tableCity = "cities";
    protected $tableDistrict = "districts";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->tableProvince)){
            Schema::table($this->tableProvince, function (Blueprint $table) {
                $table->string("code")->after("name")->index();
            });
        }
        if(Schema::hasTable($this->tableCity)){
            Schema::table($this->tableCity, function (Blueprint $table) {
                $table->string("code")->after("name")->index();
            });
        }
        if(Schema::hasTable($this->tableDistrict)){
            Schema::table($this->tableDistrict, function (Blueprint $table) {
                $table->string("code")->after("name")->index();
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
        if(Schema::hasTable($this->tableProvince)){
            Schema::table($this->tableProvince, function (Blueprint $table) {
                $table->dropColumn(["code"]);
            });
        }
        if(Schema::hasTable($this->tableCity)){
            Schema::table($this->tableCity, function (Blueprint $table) {
                $table->dropColumn(["code"]);
            });
        }
        if(Schema::hasTable($this->tableDistrict)){
            Schema::table($this->tableDistrict, function (Blueprint $table) {
                $table->dropColumn(["code"]);
            });
        }
    }
};
