<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->tinyInteger('state')->default(1);
            $table->text('description')->nullable();
            $table->text('params')->nullable();
            $table->unsignedBigInteger('ordering')->nullable();
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('assets_groups');
    }
}
