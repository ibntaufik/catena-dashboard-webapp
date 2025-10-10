<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableFarm = "farms";
    protected $tableShadeTree = "master_shade_tree";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableShadeTree)){
            Schema::create($this->tableShadeTree, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name', 255);

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();
            });
        }

        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->dropColumn(['shadowing_tree']);
            $table->unsignedInteger('coffee_id')->nullable()->index()->after("supplier_id");
            $table->unsignedInteger('coffee_variety_id')->nullable()->index()->after("coffee_id");
            $table->unsignedInteger('shade_tree_id')->nullable()->index()->after("coffee_variety_id");
            $table->text('address')->nullable()->after('shade_tree_id');
            $table->string('land_certificate')->nullable()->after('address');;
            $table->foreign('coffee_id')->references('id')->on("master_coffee");
            $table->foreign('coffee_variety_id')->references('id')->on("master_coffee_variety");
            $table->foreign('shade_tree_id')->references('id')->on($this->tableShadeTree);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableFarm, function (Blueprint $table) {
            $table->dropForeign($this->tableFarm.'_coffee_id_foreign');
            $table->dropForeign($this->tableFarm.'_coffee_variety_id_foreign');
            $table->dropForeign($this->tableFarm.'_shade_tree_id_foreign');
            $table->dropColumn(['coffee_id', 'coffee_variety_id', 'shade_tree_id']);

            $table->string('shadowing_tree')->nullable();
        });
    }
};
