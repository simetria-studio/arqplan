<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financeaccount', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description')->nullable();
            $table->float('initial')->default(0);
            
            $table->unsignedBigInteger('company_id')->nullable(false);

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });

        
        Schema::create('financetransactioncategory', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');

            $table->unsignedBigInteger('company_id')->nullable();

            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });

        
        Schema::create('financetransaction', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('description')->nullable();
            $table->date('date');
            $table->char('type', 1)->default("C");
            $table->float('amount')->default(0);
            $table->char('status', 3)->default("OK");
            $table->string('people_type')->nullable();            

            $table->unsignedBigInteger('financeaccount_id');
            $table->unsignedBigInteger('financecategory_id')->nullable();
            $table->unsignedBigInteger('people_id')->nullable();
            $table->unsignedBigInteger('company_id');
            
            $table->foreign('financeaccount_id')->references('id')->on('financeaccount')->onDelete('cascade');
            $table->foreign('financecategory_id')->references('id')->on('financetransactioncategory')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
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
        Schema::dropIfExists('financetransaction');
        Schema::dropIfExists('financetransactioncategory');
        Schema::dropIfExists('financeaccount');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
