<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('nationality')->default('bangladeshi');
            $table->string('passport_number');
            $table->string('photo_path');
            $table->text('personal_address');
            $table->string('city');
            $table->string('phone');
            $table->integer('designation_id');
            $table->integer('department_id');
            $table->integer('salary_id');
            $table->integer('working_days_id');
            $table->date('joining_date');
            $table->boolean('status')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
