<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('users_companies', 'validated')) {
                $table->unsignedTinyInteger('validated')->default(0)->after('issueOther');
            }

            if (!Schema::hasColumn('users_companies', 'validateToken')) {
                $table->string('validateToken')->nullable()->after('validated');
            }

            if (!Schema::hasColumn('users_companies', 'lastValidate')) {
                $table->dateTime('lastValidate')->nullable()->after('validateToken');
            }

            if (!Schema::hasColumn('users_companies', 'isExpired')) {
                $table->unsignedTinyInteger('isExpired')->default(0)->after('lastValidate');
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
