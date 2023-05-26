<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoothCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booth_categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name');
            $table->integer('priority');
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
        Schema::dropIfExists('booth_categories');
    }
}
