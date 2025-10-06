<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "farmers";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("account_farmer");

        if(!Schema::hasTable($this->table)){
            Schema::create($this->table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedInteger('vch_id')->nullable()->index();
                $table->string('code', 17)->index();
                $table->unsignedInteger('sub_district_id')->index();
                $table->text('address');
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->string('id_number', 255)->index();
                $table->text('thumb_finger')->nullable();
                $table->text('index_finger')->nullable();
                $table->text('image_id_number_name')->nullable();
                $table->text('image_photo_name')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users');
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