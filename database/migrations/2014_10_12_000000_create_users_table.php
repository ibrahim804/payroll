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
            $table->integer('employee_id')->nullable();
            $table->string('full_name');
            $table->string('user_name')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();     // no need in this application
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('gender');
            $table->string('marital_status')->nullable();
            $table->string('nationality')->default('bangladeshi');
            $table->text('permanent_address')->nullable();
            $table->text('present_address')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('phone');
            $table->integer('company_id')->nullable();
            $table->integer('designation_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('role_id');
            $table->integer('id_of_leader');
            $table->integer('salary_id')->nullable();
            $table->integer('working_day_id')->nullable();
            $table->date('joining_date');
            $table->integer('deposit_pf')->default(0);
            $table->integer('verification_code')->nullable();       // code is sent to user's email when password is forgot
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
