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
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_name');
            $table->string('nickname')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('job')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->string('zipcode')->nullable();

            $table->string('identity')->nullable();
            $table->string('unit')->nullable();
            $table->string('unit_department')->nullable();
            $table->string('job_title')->nullable();
            $table->string('school')->nullable();
            $table->string('school_department')->nullable();
            $table->string('grade')->nullable();
            $table->text('how')->nullable();
            $table->boolean('subscription')->default(0);

            $table->string('image')->nullable();
            $table->boolean('activation')->default(0);
            $table->string('activate_token');
            $table->unsignedTinyInteger('block')->nullable()->default(0);
            $table->unsignedTinyInteger('reset_password')->default(0);
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('timezone')->nullable();
            $table->string('locale')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
