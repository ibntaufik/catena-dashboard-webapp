<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "master_locality";
    protected $tableAssets = "master_locality_assets";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->table)){
            Schema::create($this->table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('sub_district_id')->index();
                $table->string('project_name', 255)->index();
                $table->string('assigned_to', 255)->index()->nullable();
                $table->string('longitude', 255)->index()->nullable();
                $table->string('latitude', 255)->index()->nullable();
                $table->text("field_verification")->nullable();
                $table->text("additional_information")->nullable();
                $table->string("locality_status", 15)->default("invalid")->nullable();
                $table->timestamp('local_created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('sub_district_id')->references('id')->on("sub_districts");
            });
        }
        if(!Schema::hasTable($this->tableAssets)){
            Schema::create($this->tableAssets, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('locality_id')->index();
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('locality_id')->references('id')->on($this->table);
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
        Schema::dropIfExists($this->tableAssets);
        Schema::dropIfExists($this->table);
    }
};
