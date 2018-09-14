<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->nestedSet();
            $table->string('title')->nullable();
            $table->string('path')->nullable();
            $table->string('full_path')->nullable();
            $table->string('component')->nullable();
            $table->string('type');
            $table->tinyInteger('state');
            $table->string('redirect')->nullable();
            $table->string('icon');
            $table->boolean('showNav');
            $table->unsignedInteger('ordering');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
