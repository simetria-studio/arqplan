<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event', function (Blueprint $table) {
            $table->boolean('recurrent')->default(false)->before('user_id')->nullable();
            $table->string('recurrentId')->before('user_id')->nullable();
            $table->string('recurrentType')->before('user_id')->nullable();
            $table->string('recurrentLimit')->before('user_id')->nullable();
            $table->integer('recurrentLimitTimes')->before('user_id')->nullable();
            $table->dateTime('recurrentLimitDate')->before('user_id')->nullable();
            $table->boolean('recurrentWeekday2')->before('user_id')->nullable();
            $table->boolean('recurrentWeekday3')->before('user_id')->nullable();
            $table->boolean('recurrentWeekday4')->before('user_id')->nullable();
            $table->boolean('recurrentWeekday5')->before('user_id')->nullable();
            $table->boolean('recurrentWeekday6')->before('user_id')->nullable();
            $table->boolean('recurrentWeekdayS')->before('user_id')->nullable();
            $table->boolean('recurrentWeekdayD')->before('user_id')->nullable();
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
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['recurrent', 'recurrentId', 'recurrentType', 'recurrentLimit', 'recurrentLimitTimes', 'recurrentLimitDate', 'recurrentWeekday2', 'recurrentWeekday3', 'recurrentWeekday4', 'recurrentWeekday5', 'recurrentWeekday6', 'recurrentWeekdayS', 'recurrentWeekdayD']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
