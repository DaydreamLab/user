<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nestedSet();
            $table->string('title');
            $table->string('alias');
            $table->string('path');
            $table->tinyInteger('state')->default(1);
            $table->text('introimage')->nullable();
            $table->text('introtext')->nullable();
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('hits')->nullable()->default(0);
            $table->unsignedInteger('access');
            $table->unsignedInteger('ordering')->nullable();
            $table->text('params')->nullable();
            $table->text('extrafields')->nullable();
            $table->text('extrafields_search')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable()->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('locked_at')->nullable();
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
        Schema::dropIfExists('companies_categories');
    }
}
