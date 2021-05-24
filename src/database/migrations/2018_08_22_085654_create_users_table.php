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
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('nickname')->nullable();
            $table->string('gender')->nullable();
            $table->string('image')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phoneCode')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobilePhoneCode')->nullable();
            $table->string('mobilePhone')->nullable();
            $table->string('country')->nullable();
            $table->string('state_')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->string('zipcode')->nullable();
            $table->boolean('activation')->default(0);
            $table->string('activateToken')->nullable();
            $table->unsignedTinyInteger('block')->nullable()->default(0);
            $table->unsignedTinyInteger('canDelete')->nullable()->default(1);
            $table->unsignedTinyInteger('resetPassword')->default(0);
            $table->timestamp('lastResetAt')->nullable();
            $table->string('lastPassword')->nullable();
            $table->timestamp('lastLoginAt')->nullable();
            $table->string('lastLoginIp')->nullable();
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
