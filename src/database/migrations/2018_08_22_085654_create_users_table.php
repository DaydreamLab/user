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
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('image')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('school')->nullable();
            $table->string('job')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zipcode')->nullable();
            $table->boolean('activation')->default(0);
            $table->string('activate_token');
            $table->string('redirect')->nullable();
            $table->unsignedTinyInteger('reset_password')->default(0);
            $table->string('timezone')->nullable();
            $table->char('locale', '2')->nullable();
            $table->rememberToken();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
