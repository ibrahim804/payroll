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
            $table->boolean('Sunday')->nullable()->default(0);
            $table->boolean('Monday')->nullable()->default(0);
            $table->boolean('Tuesday')->nullable()->default(0);
            $table->boolean('Wednesday')->nullable()->default(0);
            $table->boolean('Thursday')->nullable()->default(0);
            $table->boolean('Friday')->nullable()->default(0);
            $table->boolean('Saturday')->nullable()->default(0);
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
