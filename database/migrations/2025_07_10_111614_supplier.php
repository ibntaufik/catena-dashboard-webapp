<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableSupply = "supply";
    protected $tableSupplier = "master_supplier";
    protected $tableSupplierCategory = "supplier_category";
    protected $tableSupplierCategories = "master_supply_categories";
    protected $tableSupplierBank = "supplier_bank";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableSupplier)){
            Schema::create($this->tableSupplier, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('nik', 20)->index()->nullable();
                $table->string('nik_photo', 255)->index()->nullable();
                $table->string('name', 255)->index();
                $table->string('alias', 255)->index()->nullable();

                $table->unsignedInteger('sub_district_id');
                $table->string('verification_status', 50)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('sub_district_id')->nullable()->references('id')->on("sub_districts");
            });
        }
        if(!Schema::hasTable($this->tableSupplierCategories)){
            Schema::create($this->tableSupplierCategories, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 20)->index();
                $table->string('name', 255)->index();
                $table->string('level', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable($this->tableSupplierCategory)){
            Schema::create($this->tableSupplierCategory, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');

                $table->unsignedInteger('supplier_id');
                $table->foreign('supplier_id')->nullable()->references('id')->on($this->tableSupplier);

                $table->unsignedInteger('category_id');
                $table->foreign('category_id')->nullable()->references('id')->on($this->tableSupplierCategories);

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }


        if(!Schema::hasTable($this->tableSupply)){
            Schema::create($this->tableSupply, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('record_id', 36)->index();
                $table->string('user_code', 20)->index();
                $table->unsignedInteger('vch_id')->nullable();
                $table->unsignedInteger('vcp_id')->nullable();
                $table->unsignedInteger('supplier_id');
                $table->string('latitude', 255)->index()->nullable();
                $table->string('longitude', 255)->index()->nullable();
                $table->string('altitude', 255)->index()->nullable();
                $table->string('nid_generate', 255)->index()->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('vch_id')->nullable()->references('id')->on("t_vch");
                $table->foreign('vcp_id')->nullable()->references('id')->on("t_vcp");
                $table->foreign('supplier_id')->nullable()->references('id')->on($this->tableSupplier);
            });
        }
        if(!Schema::hasTable($this->tableSupplierBank)){
            Schema::create($this->tableSupplierBank, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('bank_id');
                $table->unsignedInteger('supplier_id');
                $table->string('bank_account', 255)->index();
                $table->string('bank_account_photo', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('bank_id')->nullable()->references('id')->on("bank");
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
        Schema::dropIfExists($this->tableSupply);
        Schema::dropIfExists($this->tableSupplierCategory);
        Schema::dropIfExists($this->tableSupplierCategories);
        Schema::dropIfExists($this->tableSupplier);
        Schema::dropIfExists($this->tableSupplierBank);
    }
};
