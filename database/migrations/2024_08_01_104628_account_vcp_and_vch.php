<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    protected $tableVcp = "vcp_account";
    protected $tableVch = "vch_account";
    protected $tableBank = "bank";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        if(!Schema::hasTable($this->tableVcp)){
            Schema::create($this->tableVcp, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('vcp_code', 255)->index();
                $table->unsignedInteger('location_id')->index();
                $table->text('address');
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->string('field_coordinator_id', 255)->index();
                $table->string('field_coordinator_name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
                
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('location_id')->references('id')->on('location');
            });
        }
        if(!Schema::hasTable($this->tableBank)){
            Schema::create($this->tableBank, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 255)->index();
                $table->string('name', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable($this->tableVch)){
            Schema::create($this->tableVch, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('vch_code', 255)->index();
                $table->unsignedInteger('location_id')->index();
                $table->text('address');
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->string('vendor_id', 255)->index();
                $table->string('vendor_name', 255)->index();
                $table->unsignedInteger('bank_id')->index();
                $table->text('vendor_bank_address');
                $table->string('vendor_bank_account_number', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('location_id')->references('id')->on('location');
                $table->foreign('bank_id')->references('id')->on('bank');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists($this->tableVcp);
        Schema::dropIfExists($this->tableVch);
        Schema::dropIfExists($this->tableBank);
    }
};
