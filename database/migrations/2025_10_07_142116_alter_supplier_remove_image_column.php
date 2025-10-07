<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "master_supplier";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->dropColumn(["image_id_number_name", "image_photo_name"]);
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
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->text('image_id_number_name')->nullable()->after("index_finger");
                $table->text('image_photo_name')->nullable()->after("image_id_number_name");
            });
        }
    }
};