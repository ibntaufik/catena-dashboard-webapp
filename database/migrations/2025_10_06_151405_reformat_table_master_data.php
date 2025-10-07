<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "master_supplier";
    protected $tableBank = "master_bank";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable("supplier_category")){
            Schema::dropIfExists("supplier_category");
        }
        if(Schema::hasTable("supplier_bank")){
            Schema::dropIfExists("supplier_bank");
        }
        if(Schema::hasTable("bank")){
            Schema::dropIfExists("bank");
        }

        if(!Schema::hasTable($this->tableBank)){
            Schema::create($this->tableBank, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 20)->index();
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }

        if(!Schema::hasTable($this->table)){
            Schema::create($this->table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 255)->index();
                $table->string('alias', 255)->index()->nullable();
                $table->string('code', 17)->index();
                $table->string('phone', 17)->index()->nullable();
                $table->string('email', 100)->index()->nullable();
                $table->unsignedInteger('vch_id')->nullable()->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->text('address')->nullable();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->string('id_number', 255)->index();
                $table->text('thumb_finger')->nullable();
                $table->text('index_finger')->nullable();
                $table->text('image_id_number_name')->nullable();
                $table->text('image_photo_name')->nullable();
                $table->unsignedInteger('category_id')->index();
                $table->unsignedInteger('bank_id')->index()->nullable();
                $table->string('account_name', 255)->nullable();
                $table->string('account_number', 255)->nullable();
                $table->string('account_photo', 255)->nullable();
                $table->string('verification_status', 50)->index()->default("non_verified");
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('category_id')->references('id')->on('master_supply_categories');
                $table->foreign('bank_id')->references('id')->on($this->tableBank);
                $table->foreign('sub_district_id')->references('id')->on('sub_districts');
                $table->foreign('vch_id')->references('id')->on('t_vch');
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
        Schema::dropIfExists($this->table);
    }
};
