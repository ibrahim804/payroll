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
            $table->string('sunday')->default('false');
            $table->string('monday')->default('false');
            $table->string('tuesday')->default('false');
            $table->string('wednesday')->default('false');
            $table->string('thursday')->default('false');
            $table->string('friday')->default('false');
            $table->string('saturday')->default('false');
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
