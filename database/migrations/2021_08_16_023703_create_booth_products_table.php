<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoothProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booth_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booth_id')->unsigned()->index();
            $table->foreign('booth_id')->references('id')->on('exhibitors')->onDelete('cascade');
            $table->string('name');
            // $table->text('description');
            $table->string('price');
            // $table->integer('stock');
            $table->string('image');
            $table->string('url');
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
        Schema::dropIfExists('booth_products');
    }
}
