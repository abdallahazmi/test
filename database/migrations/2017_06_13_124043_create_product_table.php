<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('products', function (blueprint $table) {

        $table->increments('id');
        $table->string('title')->nullable();
        $table->string('description')->nullable();
        $table->string('category_id')->nullable();

        $table->integer('user_id')->unsigned();
        $table->foreign('user_id', 'au_id_foreign')->references('id')->on('users')->onDelete('cascade');

        $table->string('image')->nullable();

        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');

    }
}
