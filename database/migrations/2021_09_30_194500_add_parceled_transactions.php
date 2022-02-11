<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParceledTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financetransaction', function (Blueprint $table) {
            $table->boolean('parceled')->default(false)->after('people_type')->nullable();
            $table->string('parceledId')->after('parceled')->nullable();
            $table->integer('parcelNumber')->after('parceledId')->nullable();
            $table->integer('parceledTimes')->after('parcelNumber')->nullable();
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
        Schema::table('financetransaction', function (Blueprint $table) {
            $table->dropColumn(['parceled', 'parceledId', 'parcelNumber', 'parceledTimes']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
