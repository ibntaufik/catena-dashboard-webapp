<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "purchase_order";
    protected $tableItemType = "item_type";
    protected $tableItemUnit = "item_unit";
    protected $tableItem = "item";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableItem)){
            Schema::create($this->tableItem, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 17)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable($this->tableItemType)){
            Schema::create($this->tableItemType, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 17)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
        if(!Schema::hasTable($this->tableItemUnit)){
            Schema::create($this->tableItemUnit, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 17)->index();
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
                $table->unsignedInteger('account_vch_id')->index();
                $table->string('status', 17)->default('waiting')->index();
                $table->string('po_number', 17)->index();
                $table->date('po_date')->index();
                $table->date('expected_shipping_date')->index();
                $table->unsignedInteger('item_id')->index();
                $table->unsignedInteger('item_type_id')->index();
                $table->decimal('item_quantity', 10, 2)->index();
                $table->unsignedInteger('item_unit_id')->index();
                $table->decimal('item_unit_price', 10, 2)->index();
                $table->decimal('item_max_quantity', 10, 2)->index();
                $table->text('item_description');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('account_vch_id')->references('id')->on("account_vch");
                $table->foreign('item_id')->references('id')->on($this->tableItem);
                $table->foreign('item_type_id')->references('id')->on($this->tableItemType);
                $table->foreign('item_unit_id')->references('id')->on($this->tableItemUnit);
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
        Schema::dropIfExists($this->tableItem);
        Schema::dropIfExists($this->tableItemType);
        Schema::dropIfExists($this->tableItemUnit);
    }
};
