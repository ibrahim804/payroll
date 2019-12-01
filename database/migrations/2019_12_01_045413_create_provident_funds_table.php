<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidentFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provident_funds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('month');
            $table->integer('year');
            $table->integer('month_count');
            $table->double('opening_balance');
            $table->double('deposit_amount_on_first_fifteen_days')->default(0);
            $table->timestamp('deposit_date_on_first_fifteen_days')->nullable();
            $table->double('deposit_amount_on_second_fifteen_days')->default(0);
            $table->timestamp('deposit_date_on_second_fifteen_days')->nullable();
            $table->double('withdraw_amount_on_first_fifteen_days')->default(0);
            $table->timestamp('withdraw_date_on_first_fifteen_days')->nullable();
            $table->double('withdraw_amount_on_second_fifteen_days')->default(0);
            $table->timestamp('withdraw_date_on_second_fifteen_days')->nullable();
            $table->double('lowest_balance')->default(0);
            $table->double('rate');
            $table->double('interest_for_this_month')->default(0);
            $table->double('closing_balance')->default(0);
            $table->timestamps();
        });
    } // default nullable

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provident_funds');
    }
}
