<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhibitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhibitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id'); //definisi kolom
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade'); //foreign key
            $table->string('name');
            $table->string('email');
            $table->string('category');
            $table->string('address');
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('website')->nullable();
            $table->boolean('virtual_booth')->nullable();
            $table->string('booth_link')->nullable();
            $table->string('logo');
            $table->string('booth_image');
            $table->string('phone')->nullable();
            $table->text('description');
            $table->string('video')->nullable();
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
        Schema::dropIfExists('exhibitors');
    }
}
