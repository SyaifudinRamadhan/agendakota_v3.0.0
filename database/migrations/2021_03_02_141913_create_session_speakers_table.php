<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionSpeakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_speakers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rundown_id')->unsigned()->index();
            $table->foreign('rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
            $table->bigInteger('speaker_id')->unsigned()->index();
            $table->foreign('speaker_id')->references('id')->on('speakers')->onDelete('cascade');
            $table->integer('deleted');
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
        Schema::dropIfExists('session_speakers');
    }
}
