<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('month');
            $table->string('year');
            $table->integer('month_count');
            $table->integer('contract_duration');
            $table->double('actual_loan_amount');
            $table->double('current_loan_amount');
            $table->double('paid_this_month');
            $table->double('total_paid_amount');
            $table->string('loan_status');
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
        Schema::dropIfExists('loan_histories');
    }
}
