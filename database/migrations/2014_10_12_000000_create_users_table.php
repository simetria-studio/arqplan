<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_super_admin')->default(false);
            $table->string('name');
            $table->string('lastname');
            $table->string('cpf')->unique()->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('password');
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->rememberToken();
            
            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
        
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code');
            $table->string('name');
        });
        
        Schema::create('user_user_profile', function (Blueprint $table) {
            $table->primary(['user_id', 'user_profile_id']);
            $table->timestamps();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_profile_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_profile_id')->references('id')->on('user_profile');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_profile');
        Schema::dropIfExists('user_user_profile');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
