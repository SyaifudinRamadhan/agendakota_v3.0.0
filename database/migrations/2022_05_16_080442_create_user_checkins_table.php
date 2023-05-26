<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_checkins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('purchase_id')->unsigned()->index();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->integer('checkin');
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
        Schema::dropIfExists('user_checkins');
    }
}
