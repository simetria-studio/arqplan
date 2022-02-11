<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('project_category', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_public')->default(true);
            $table->string('name');
            
            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('set null');
        });
        
        Schema::create('project_status', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');

            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
        
        Schema::create('project_step', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');

            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });

        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('code');
            $table->string('name');
            $table->longText('scope');
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            
            $table->unsignedBigInteger('company_id')->nullable(false);
            $table->unsignedBigInteger('client_id')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('project_category')->onDelete('set null');
        });
        
        Schema::create('project_step_status', function (Blueprint $table) {
            $table->primary(['project_id', 'project_step_id']);
            $table->timestamps();
            
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('project_step_id');
            $table->unsignedBigInteger('project_status_id');
            $table->integer('position')->nullable();
            $table->dateTime('endDate')->nullable();

            $table->foreign('project_id')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('project_step_id')->references('id')->on('project_step')->onDelete('cascade');
            $table->foreign('project_status_id')->references('id')->on('project_status')->onDelete('cascade');
        });
        
        Schema::create('project_user', function (Blueprint $table) {
            $table->primary(['user_id', 'project_id']);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('project');
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
        Schema::dropIfExists('project_category');
        Schema::dropIfExists('project_step');
        Schema::dropIfExists('project_status');
        Schema::dropIfExists('project_step_status');
        Schema::dropIfExists('project');
        Schema::dropIfExists('project_user');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
