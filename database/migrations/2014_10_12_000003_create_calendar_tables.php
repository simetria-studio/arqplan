<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_public')->default(true);
            $table->string('title');
            $table->char('type', 1)->default("E");
            $table->string('description')->nullable();
            $table->dateTime('start')->nullable(false);
            $table->dateTime('end')->nullable(false);

            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('company_id')->nullable(false);
            $table->unsignedBigInteger('project_id')->nullable(true);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
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
        Schema::dropIfExists('event');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
