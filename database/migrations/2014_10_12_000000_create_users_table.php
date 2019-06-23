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
            $table->integer('employee_id');
            $table->string('full_name');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth');
            $table->string('fathers_name');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('nationality')->default('bangladeshi');
            $table->text('permanent_address');
            $table->text('present_address');
            $table->string('passport_number');
            $table->string('photo_path')->nullable();
            $table->string('phone');
            $table->integer('designation_id');
            $table->integer('department_id');
            $table->integer('salary_id')->nullable();
            $table->integer('working_days_id');
            $table->date('joining_date');
            $table->boolean('status')->default(1)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
