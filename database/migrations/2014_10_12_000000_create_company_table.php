<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('cnpjcpf')->nullable(false);
            $table->text('description');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            
            $table->string('zipcode')->nullable();
            $table->string('address')->nullable();
            $table->string('addressnumber')->nullable();
            $table->string('addresscomplement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('approved')->default(0);
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
        Schema::dropIfExists('company');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
