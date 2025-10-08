<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "master_supplier";
    protected $tableBusinessType = "master_business_type";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableBusinessType)){
            Schema::create($this->tableBusinessType, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('code', 30);
                $table->string('name', 255);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

            });
        }

        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->unsignedInteger('business_type_id')->nullable()->index()->after("address");
                $table->string('business_name', 255)->nullable()->index()->after("business_type_id");
                $table->string('authentication_code', 30)->nullable()->index()->after("business_name");
                $table->string('npwp', 30)->nullable()->index()->after("authentication_code");

                $table->foreign('business_type_id')->references('id')->on($this->tableBusinessType);
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
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropForeign($this->table.'_business_type_id_foreign');
            $table->dropColumn(['business_type_id','business_name','authentication_code','npwp']);
        });

        Schema::dropIfExists($this->tableBusinessType);
    }
};
