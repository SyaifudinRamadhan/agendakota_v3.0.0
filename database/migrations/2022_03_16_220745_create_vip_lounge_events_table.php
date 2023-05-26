<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVipLoungeEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip_lounge_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('session_id')->unsigned()->index();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->integer('table_count');
            $table->integer('chair_table');
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
        Schema::dropIfExists('vip_lounge_events');
    }
}
