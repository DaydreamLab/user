<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsApisMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_apis_maps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_group_id');
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('api_id');
            $table->unsignedTinyInteger('disabled')->default(0);
            $table->unsignedTinyInteger('hidden')->default(0);
            $table->unsignedTinyInteger('checked')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets_apis_maps');
    }
}
