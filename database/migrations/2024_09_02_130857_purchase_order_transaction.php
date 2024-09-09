<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "purchase_order_transaction";
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
                $table->unsignedInteger('purchase_order_id')->index();
                $table->unsignedInteger('account_farmer_id')->index();
                $table->string('transaction_id', 20);
                $table->string('receipt_number', 20)->nullable();
                $table->date('transaction_date')->index();
                $table->unsignedInteger('item_quantity')->index();
                $table->decimal('floating_rate', 5, 2)->index();
                $table->decimal('total_item_price', 18, 2)->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->string('created_by', 255)->default("System");
                $table->timestamp('updated_at')->nullable();
                $table->string('updated_by', 255)->nullable();
                $table->softDeletes();

                $table->foreign('purchase_order_id')->references('id')->on("purchase_order");
                $table->foreign('account_farmer_id')->references('id')->on("account_farmer");
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
