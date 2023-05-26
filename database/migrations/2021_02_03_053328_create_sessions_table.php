<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('start_rundown_id')->unsigned()->nullable()->index();
            $table->foreign('start_rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
            $table->bigInteger('end_rundown_id')->unsigned()->nullable()->index();
            $table->foreign('end_rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
            $table->string('title');
            $table->text('link')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('overview');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
