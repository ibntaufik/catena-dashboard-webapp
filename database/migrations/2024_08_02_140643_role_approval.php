<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tableRoleApprovalAt = "role_approval_at";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        if(!Schema::hasTable($this->tableRoleApprovalAt)){
            Schema::create($this->tableRoleApprovalAt, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('role_at', 255)->index();
                $table->unsignedInteger('vcp_account_id')->nullable()->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('vcp_account_id')->references('id')->on('vcp_account');
            });
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists($this->tableRoleApprovalAt);
    }
};
