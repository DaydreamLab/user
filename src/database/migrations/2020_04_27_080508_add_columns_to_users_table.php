<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->after('zipcode')->nullable();
            $table->string('tax_id_number')->after('company_name')->nullable();
            $table->string('company_tel_locale')->after('tax_id_number')->nullable();
            $table->string('company_tel_number')->after('company_tel_locale')->nullable();
            $table->string('company_tel_extension')->after('company_tel_number')->nullable();
            $table->string('mobile_phone')->after('company_tel_extension')->nullable();
            $table->string('department')->after('mobile_phone')->nullable();
            $table->string('job_title')->after('department')->nullable();
            $table->boolean('become_zerone_member')->after('job_title')->nullable();
            $table->boolean('zerone_subscriptions')->after('become_zerone_member')->nullable();
            $table->boolean('zerone_breaking_news')->after('zerone_subscriptions')->nullable();
            $table->unsignedTinyInteger('score_id')->after('zerone_breaking_news')->nullable();
            $table->unsignedTinyInteger('is_black_list')->after('score_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
