<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_days', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Sunday')->default('false');
            $table->string('Monday')->default('false');
            $table->string('Tuesday')->default('false');
            $table->string('Wednesday')->default('false');
            $table->string('Thursday')->default('false');
            $table->string('Friday')->default('false');
            $table->string('Saturday')->default('false');
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
        Schema::dropIfExists('working_days');
    }
}
