<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notificationId')->nullable();
            $table->string('phoneCode');
            $table->string('phone');
            $table->string('category');
            $table->string('type');
            $table->text('message');
            $table->string('messageId');
            $table->unsignedInteger('messageCount')->default(0);
            $table->unsignedInteger('messageLength')->default(0);
            $table->unsignedTinyInteger('success');
            $table->string('responseCode')->nullable();
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
        Schema::dropIfExists('sms_histories');
    }
}
