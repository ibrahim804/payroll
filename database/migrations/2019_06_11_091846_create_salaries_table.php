<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->double('basic_salary');
            $table->double('house_rent_allowance')->nullable();
            $table->double('medical_allowance')->nullable();
            $table->double('special_allowance')->nullable();
            $table->double('fuel_allowance')->nullable();
            $table->double('phone_bill_allowance')->nullable();
            $table->double('other_allowance')->nullable();
            $table->double('tax_deduction')->nullable();
            $table->double('provident_fund')->nullable();
            $table->double('other_deduction')->nullable();
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
        Schema::dropIfExists('salaries');
    }
}
