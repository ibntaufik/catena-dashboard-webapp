<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableCoffee = "master_coffee";
    protected $tableCoffeeVariety = "master_coffee_variety";
    protected $tableFarm = "farms";
    protected $tableFarmLand = "farm_detail";
    protected $tableCoffeeFarmLand = "coffee_farm_detail";
    protected $tableCoffeeVarietyFarmLand = "coffee_variety_farm_detail";
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable($this->tableCoffee)){
            Schema::create($this->tableCoffee, function (Blueprint $table) {
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

        if(!Schema::hasTable($this->tableCoffeeVariety)){
            Schema::create($this->tableCoffeeVariety, function (Blueprint $table) {
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

        if(!Schema::hasTable($this->tableFarm)){
            Schema::create($this->tableFarm, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('supply_id');
                $table->string('latitude', 255)->index()->nullable();
                $table->string('longitude', 255)->index()->nullable();
                $table->string('altitude', 255)->index()->nullable();
                $table->decimal('elevation', 8, 2)->index()->nullable();
                $table->decimal('land_measurement', 8, 2)->index()->nullable();
                
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('supply_id')->nullable()->references('id')->on("supply");
            });
        }

        if(!Schema::hasTable($this->tableFarmLand)){
            Schema::create($this->tableFarmLand, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                
                $table->unsignedInteger('farm_id')->nullable();
                $table->bigInteger('tree_population')->index()->nullable();
                $table->string('land_status', 255)->nullable();
                $table->string('shadowing_tree', 255)->nullable();
                $table->string('farm_photo', 255)->nullable();

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
                
                $table->foreign('farm_id')->nullable()->references('id')->on($this->tableFarm);
            });
        }

        if(!Schema::hasTable($this->tableCoffeeFarmLand)){
            Schema::create($this->tableCoffeeFarmLand, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('coffee_id');
                $table->unsignedInteger('farm_detail_id');

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('coffee_id')->nullable()->references('id')->on($this->tableCoffee);
                $table->foreign('farm_detail_id')->nullable()->references('id')->on($this->tableFarmLand);
            });
        }

        if(!Schema::hasTable($this->tableCoffeeVarietyFarmLand)){
            Schema::create($this->tableCoffeeVarietyFarmLand, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('coffee_variety_id');
                $table->unsignedInteger('farm_detail_id');

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('coffee_variety_id')->nullable()->references('id')->on($this->tableCoffeeVariety);
                $table->foreign('farm_detail_id')->nullable()->references('id')->on($this->tableFarmLand);
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
        Schema::dropIfExists($this->tableCoffeeFarmLand);
        Schema::dropIfExists($this->tableCoffeeVarietyFarmLand);

        Schema::dropIfExists($this->tableFarmLand);
        Schema::dropIfExists($this->tableFarm);
        
        Schema::dropIfExists($this->tableCoffeeVariety);
        Schema::dropIfExists($this->tableCoffee);
    }
};
