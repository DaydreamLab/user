<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('users_companies', 'jobCategory')) {
                $table->string('jobCategory')->after('department')->nullable();
            }
            if (!Schema::hasColumn('users_companies', 'jobType')) {
                $table->string('jobType')->after('jobTitle')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_companies', function (Blueprint $table) {
            //
        });
    }
}
