<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableEvc = "t_evc";
    protected $tableVch = "t_vch";
    protected $tableVcp = "t_vcp";
    protected $tableAccount = "accounts";
    protected $tableVchAccount = "account_vch";
    protected $tableVcpAccount = "account_vcp";
    protected $tableEvcAccount = "account_evc";
    protected $tableBank = "bank";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

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

        if(!Schema::hasTable($this->tableEvc)){
            Schema::create($this->tableEvc, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 20)->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->text("address");
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('sub_district_id')->references('id')->on('sub_districts');
            });
        }
        if(!Schema::hasTable($this->tableVch)){
            Schema::create($this->tableVch, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 20)->index();
                $table->unsignedInteger('evc_id')->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->text("address");
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('evc_id')->references('id')->on($this->tableEvc);
                $table->foreign('sub_district_id')->references('id')->on('sub_districts');
            });
        }
        if(!Schema::hasTable($this->tableVcp)){
            Schema::create($this->tableVcp, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 20)->index();
                $table->unsignedInteger('vch_id')->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->text("address");
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('vch_id')->references('id')->on($this->tableVch);
                $table->foreign('sub_district_id')->references('id')->on('sub_districts');
            });
        }
        if(!Schema::hasTable($this->tableAccount)){
            Schema::create($this->tableAccount, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 50)->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('status', 20)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on("users");
            });
        }
        if(!Schema::hasTable($this->tableVchAccount)){
            Schema::create($this->tableVchAccount, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('vch_id')->index();
                $table->unsignedInteger('account_id')->index();
                $table->unsignedInteger('bank_id')->index();
                $table->string('vendor_bank_account_number', 100)->index();
                $table->text('vendor_bank_address');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('bank_id')->references('id')->on("bank");
                $table->foreign('vch_id')->references('id')->on($this->tableVch);
                $table->foreign('account_id')->references('id')->on($this->tableAccount);
            });
        }
        if(!Schema::hasTable($this->tableVcpAccount)){
            Schema::create($this->tableVcpAccount, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('vcp_id')->index();
                $table->unsignedInteger('account_id')->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('vcp_id')->references('id')->on($this->tableVcp);
                $table->foreign('account_id')->references('id')->on($this->tableAccount);
            });
        }
        if(!Schema::hasTable($this->tableEvcAccount)){
            Schema::create($this->tableEvcAccount, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('evc_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('evc_id')->references('id')->on($this->tableEvcAccount);
                $table->foreign('user_id')->references('id')->on("users");
            });
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        
        Schema::dropIfExists($this->tableEvcAccount);
        Schema::dropIfExists($this->tableVchAccount);
        Schema::dropIfExists($this->tableVcpAccount);

        Schema::dropIfExists($this->tableVcp);
        Schema::dropIfExists($this->tableVch);
        Schema::dropIfExists($this->tableEvc);
        Schema::dropIfExists($this->tableAccount);

    }
};
