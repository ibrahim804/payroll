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
            $table->increments('id');
            $table->integer('user_id');
            $table->string('month');
            $table->string('year');
            $table->double('opening_balance');
            $table->double('gross_salary');
            $table->double('deposit_rate');
            $table->double('deposit_balance');
            $table->double('opening_and_deposit');
            $table->integer('payment_in_times');
            $table->double('company_contribution_rate');
            $table->double('company_contribution');
            $table->double('closing_balance');
            $table->timestamps();
        });
    }

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
