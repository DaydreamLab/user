<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersGroupsApisMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_groups_apis_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('asset_id')->nullable();
            $table->unsignedInteger('api_id')->nullable();
            $table->text('rules')->nullable();
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
        Schema::dropIfExists('users_groups_apis_maps');
    }
}
