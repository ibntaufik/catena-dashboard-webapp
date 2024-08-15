<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "account_farmer";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("farmer_account");

        if(!Schema::hasTable($this->table)){
            Schema::create($this->table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('code', 17)->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->text('address');
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->string('id_number', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('sub_district_id')->references('id')->on('sub_districts');
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
