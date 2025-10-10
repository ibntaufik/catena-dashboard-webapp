<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableFarm = "farms";
    protected $tableFarmDetail = "farm_detail";
    protected $tableLandStatus = "master_land_status";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableLandStatus)){
            Schema::create($this->tableLandStatus, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 255);

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }

        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->decimal('land_measurement', 8, 2)->index()->nullable()->after("altitude");
            $table->string('tree_population', 255)->index()->after("land_measurement");
            $table->string('shadowing_tree', 255)->index()->after("tree_population");
            $table->unsignedInteger('land_status_id')->index()->after("shadowing_tree");

            $table->foreign('land_status_id')->references('id')->on($this->tableLandStatus);
        });

        Schema::dropIfExists($this->tableFarmDetail);

        if(!Schema::hasTable($this->tableFarmDetail)){
            Schema::create($this->tableFarmDetail, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('farm_id')->index();
                $table->string('photo', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('farm_id')->references('id')->on($this->tableFarm);
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
        Schema::dropIfExists($this->tableFarmDetail);
        Schema::dropIfExists($this->tableLandStatus);
        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->dropForeign($this->tableFarm.'_land_status_id_foreign');
            $table->dropColumn(['tree_population', 'land_status_id', 'shadowing_tree']);
        });
    }
};
