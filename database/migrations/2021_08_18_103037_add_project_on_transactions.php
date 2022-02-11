<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectOnTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financetransaction', function (Blueprint $table) {            
            $table->unsignedBigInteger('project_id')->after('company_id')->nullable();
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
            $table->dropColumn(['project_id']);
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
