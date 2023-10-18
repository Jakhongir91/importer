<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("region")->nullable();
            $table->string("country")->nullable();
            $table->string("item_type")->nullable();
            $table->enum("sales_channel", ["Offline", "Online"])->nullable();
            $table->string("order_priority")->nullable();
            $table->date("order_date")->nullable();
            $table->unsignedBigInteger("order_id")->nullable();
            $table->date("ship_date")->nullable();
            $table->unsignedInteger("units_sold")->nullable();
            $table->unsignedBigInteger("unit_price")->nullable();
            $table->unsignedBigInteger("unit_cost")->nullable();
            $table->unsignedBigInteger("total_revenue")->nullable();
            $table->unsignedBigInteger("total_cost")->nullable();
            $table->unsignedBigInteger("total_profit")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
