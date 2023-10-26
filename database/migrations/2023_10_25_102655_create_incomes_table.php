<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string("passport_series")->nullable();
            $table->string("inps")->nullable();
            $table->double("income");
            $table->date("date");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("fullname")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
