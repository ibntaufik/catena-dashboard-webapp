<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    protected $table = "location";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        if(!Schema::hasTable($this->table)){
            Schema::create($this->table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 255)->index();
                $table->string('sub_district', 255)->index();
                $table->string('district', 255)->index();
                $table->string('city', 255)->index();
                $table->string('province', 255)->index();
                $table->string('latitude', 255)->index();
                $table->string('longitude', 255)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists($this->table);
    }
};
