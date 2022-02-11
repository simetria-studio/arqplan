<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('obs');
            $table->double('price');
            $table->string('fornecedor')->nullable();
            $table->string('unidade')->nullable();
            $table->string('tipo')->nullable();
            $table->string('altura')->nullable();
            $table->string('largura')->nullable();
            $table->string('peso')->nullable();
            $table->string('comprimento')->nullable();
            $table->string('quantidade')->nullable();
            $table->string('image')->nullable();
            $table->integer('categoria')->nullable();
            $table->string('rt')->default(0);
            $table->string('percent')->nullable();
            $table->text('observacao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
