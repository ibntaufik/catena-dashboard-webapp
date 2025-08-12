<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "provinces";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)){
            Schema::table($this->table, function (Blueprint $table) {
                $table->string('status', 30)->default("non_prioritas")->index()->after("code");
            });

            DB::statement("UPDATE ".$this->table." SET `status` = 'prioritas' WHERE `code` IN ('NAD','SMA','JAT','BLI','JAR','NTT','SWN','DKJ')");
            DB::statement("UPDATE ".$this->table." SET `status` = 'non_prioritas' WHERE `code` NOT IN ('NAD','SMA','JAT','BLI','JAR','NTT','SWN','DKJ')");
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
            $table->dropColumn('status');
        });
    }
};
