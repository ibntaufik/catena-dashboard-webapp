<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "engagement";
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
                $table->unsignedInteger('supply_id');
                $table->unsignedInteger('master_supplier_id');
                $table->date("engagement_date")->index();
                $table->string("engagement_type", 255)->index();
                $table->string("coupon_no", 255)->index()->nullable();
                $table->string("farmer_photo", 255)->index()->nullable();
                
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('supply_id')->nullable()->references('id')->on("supply");
                $table->foreign('master_supplier_id')->nullable()->references('id')->on("master_supplier");
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
