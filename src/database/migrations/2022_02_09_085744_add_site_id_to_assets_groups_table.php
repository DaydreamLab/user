<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteIdToAssetsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('assets_groups', 'site_id')) {
                $table->unsignedInteger('site_id')->default(1)->after('id');
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
        Schema::table('assets_groups', function (Blueprint $table) {
            $table->dropColumn(['site_id']);
        });
    }
}
