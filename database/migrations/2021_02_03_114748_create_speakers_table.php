<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('company');
            $table->string('job');
            $table->string('photo');
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('website')->nullable();
            $table->boolean('overview');
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
        Schema::dropIfExists('speakers');
    }
}
