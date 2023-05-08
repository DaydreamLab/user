<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsToCompanyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_orders', function (Blueprint $table) {
            if (Schema::hasColumn('company_orders', 'userId')) {
                $table->dropColumn('userId');
            }
            if (Schema::hasColumn('company_orders', 'uuid')) {
                $table->dropColumn('uuid');
            }
            if (Schema::hasColumn('company_orders', 'orderNum')) {
                $table->dropColumn('orderNum');
            }
            if (Schema::hasColumn('company_orders', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('company_orders', 'company')) {
                $table->dropColumn('company');
            }
            if (Schema::hasColumn('company_orders', 'companyName')) {
                $table->dropColumn('companyName');
            }
            if (!Schema::hasColumn('company_orders', 'companyId')) {
                $table->unsignedBigInteger('companyId')->after('id');
            }
            if (!Schema::hasColumn('company_orders', 'brandId')) {
                $table->unsignedBigInteger('brandId')->after('companyId');
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
        Schema::table('company_orders', function (Blueprint $table) {
            //
        });
    }
}
