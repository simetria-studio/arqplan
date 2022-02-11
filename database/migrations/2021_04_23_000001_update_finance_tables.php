<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFinanceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financeaccount', function (Blueprint $table) {
            $table->string('agency')->nullable();
            $table->string('account')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('financeaccount', function (Blueprint $table) {
            $table->dropColumn(['agency', 'account']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
