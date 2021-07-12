<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmssHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smss_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phoneCode');
            $table->string('phone');
            $table->string('type');
            $table->text('message');
            $table->string('MitakeMsgId');
            $table->unsignedInteger('messageCount')->default(0);
            $table->unsignedInteger('messageLength')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('smss_histories');
    }
}
