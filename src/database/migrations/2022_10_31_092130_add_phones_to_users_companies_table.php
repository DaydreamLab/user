<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhonesToUsersCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('users_companies', 'phones')) {
                $table->text('phones')->nullable()->after('vat');
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
        Schema::table('users_companies', function (Blueprint $table) {
            //
        });
    }
}
