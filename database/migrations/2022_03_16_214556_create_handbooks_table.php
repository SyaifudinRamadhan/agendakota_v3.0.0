<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handbooks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('exhibitor_id')->unsigned()->nullable()->index();
            $table->foreign('exhibitor_id')->references('id')->on('exhibitors')->onDelete('cascade');
            $table->text('file_name');
            $table->text('slug');
            $table->string('type_file');
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
        Schema::dropIfExists('handbooks');
    }
}
