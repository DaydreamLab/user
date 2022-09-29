<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'status')) {
                $table->string('status')->after('name');
            }

            if (!Schema::hasColumn('companies', 'salesInfo')) {
                $table->text('salesInfo')->after('mailDomains')->nullable();
            }

            if (!Schema::hasColumn('companies', 'phones')) {
                $table->text('phones')->after('phone')->nullable();
            }

            if (!Schema::hasColumn('companies', 'industry')) {
                $table->string('industry')->after('phones')->nullable();
            }

            if (!Schema::hasColumn('companies', 'scale')) {
                $table->string('scale')->after('industry')->nullable();
            }

            if (!Schema::hasColumn('companies', 'approvedAt')) {
                $table->dateTime('approvedAt')->after('address')->nullable();
            }

            if (!Schema::hasColumn('companies', 'expiredAt')) {
                $table->dateTime('expiredAt')->after('approvedAt')->nullable();
            }

            if (!Schema::hasColumn('companies', 'rejectedAt')) {
                $table->dateTime('rejectedAt')->after('expiredAt')->nullable();
            }

            if (!Schema::hasColumn('companies', 'expiredAt')) {
                $table->unsignedBigInteger('applyUserId')->after('expiredAt')->nullable();
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
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
