<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $table = "users";
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->string("phone", 15)->after("email")->nullable()->index();
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
                $table->dropColumn(["phone"]);
            });
        }
    }
};
