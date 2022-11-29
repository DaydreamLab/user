<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteReasonToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'reason')) {
                $table->string('reason')->after('categoryNote')->nullable();
            }

            if (Schema::hasColumn('companies', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('companies', 'rejectedAt')) {
                $table->dropColumn('rejectedAt');
            }

            if (Schema::hasColumn('companies', 'applyUserId')) {
                $table->dropColumn('applyUserId');
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
