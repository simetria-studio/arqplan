<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinanceTransactionRecurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financetransaction', function (Blueprint $table) {
            $table->boolean('recurrent')->default(false)->after('people_type')->nullable();
            $table->string('recurrentId')->after('recurrent')->nullable();
            $table->string('recurrentType')->after('recurrentId')->nullable();
            $table->string('recurrentLimit')->after('recurrentType')->nullable();
            $table->integer('recurrentLimitTimes')->after('recurrentLimit')->nullable();
            $table->dateTime('recurrentLimitDate')->after('recurrentLimitTimes')->nullable();
            $table->boolean('recurrentWeekday2')->after('recurrentLimitDate')->nullable();
            $table->boolean('recurrentWeekday3')->after('recurrentWeekday2')->nullable();
            $table->boolean('recurrentWeekday4')->after('recurrentWeekday3')->nullable();
            $table->boolean('recurrentWeekday5')->after('recurrentWeekday4')->nullable();
            $table->boolean('recurrentWeekday6')->after('recurrentWeekday5')->nullable();
            $table->boolean('recurrentWeekdayS')->after('recurrentWeekday6')->nullable();
            $table->boolean('recurrentWeekdayD')->after('recurrentWeekdayS')->nullable();
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
            $table->dropColumn(['recurrent', 'recurrentId', 'recurrentType', 'recurrentLimit', 'recurrentLimitTimes', 'recurrentLimitDate', 'recurrentWeekday2', 'recurrentWeekday3', 'recurrentWeekday4', 'recurrentWeekday5', 'recurrentWeekday6', 'recurrentWeekdayS', 'recurrentWeekdayD']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
