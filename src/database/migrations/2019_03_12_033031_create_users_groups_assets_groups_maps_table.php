<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGroupsAssetsGroupsMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_groups_assets_groups_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_group_id')->nullable();
            $table->unsignedBigInteger('asset_group_id')->nullable();
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
        Schema::dropIfExists('users_groups_assets_groups_maps');
    }
}
