<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableProvince = "provinces";
    protected $tableCity = "cities";
    protected $tableDistrict = "districts";
    protected $tableSubDistrict = "sub_districts";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableProvince)){
            Schema::create($this->tableProvince, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable($this->tableCity)){
            Schema::create($this->tableCity, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('province_id')->index();
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('province_id')->references('id')->on($this->tableProvince);
            });
        }
        if(!Schema::hasTable($this->tableDistrict)){
            Schema::create($this->tableDistrict, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('city_id')->index();
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('city_id')->references('id')->on($this->tableCity);
            });
        }
        if(!Schema::hasTable($this->tableSubDistrict)){
            Schema::create($this->tableSubDistrict, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('district_id')->index();
                $table->string('code', 255)->index();
                $table->string('name', 255)->index();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('district_id')->references('id')->on($this->tableDistrict);
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
        Schema::dropIfExists($this->tableSubDistrict);
        Schema::dropIfExists($this->tableDistrict);
        Schema::dropIfExists($this->tableCity);
        Schema::dropIfExists($this->tableProvince);
    }
};
