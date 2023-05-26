<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organizer_id')->unsigned()->index();
            $table->foreign('organizer_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->string('slug');
            $table->string('name');
            $table->string('category');
            $table->string('type');
            $table->string('punchline')->nullable();
            $table->string('logo');
            $table->text('description');
            $table->string('execution_type');
            $table->text('location');
            $table->string('province');
            $table->string('city');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_publish');
            $table->string('breakdown',212);
            $table->string('instagram',212)->nullable();
            $table->string('twitter',212)->nullable();
            $table->string('website',212)->nullable();
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
        Schema::dropIfExists('events');
    }
}
