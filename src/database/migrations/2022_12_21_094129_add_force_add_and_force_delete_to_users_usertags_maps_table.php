<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForceAddAndForceDeleteToUsersUsertagsMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_usertags_maps', function (Blueprint $table) {
            if (!Schema::hasColumn('users_usertags_maps', 'forceAdd')) {
                $table->unsignedTinyInteger('forceAdd')->default(0)->after('userTagId');
            }
            if (!Schema::hasColumn('users_usertags_maps', 'forceDelete')) {
                $table->unsignedTinyInteger('forceDelete')->default(0)->after('forceAdd');
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
        Schema::table('users_usertags_maps', function (Blueprint $table) {
            //
        });
    }
}
