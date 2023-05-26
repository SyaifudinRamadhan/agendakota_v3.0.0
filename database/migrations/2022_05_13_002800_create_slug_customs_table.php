<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlugCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slug_customs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('slug_custom', 212);
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
        Schema::dropIfExists('slug_customs');
    }
}
