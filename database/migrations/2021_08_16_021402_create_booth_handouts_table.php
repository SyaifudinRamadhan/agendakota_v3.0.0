<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoothHandoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booth_handouts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booth_id')->unsigned()->index();
            $table->foreign('booth_id')->references('id')->on('exhibitors')->onDelete('cascade');
            $table->string('type');
            $table->string('content');
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
        Schema::dropIfExists('booth_handouts');
    }
}
