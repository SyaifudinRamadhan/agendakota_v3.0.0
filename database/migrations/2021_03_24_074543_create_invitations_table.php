<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender')->unsigned()->index();
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('receiver')->unsigned()->index();
            $table->foreign('receiver')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('purchase_id')->unsigned()->index();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('ticket_id')->unsigned()->index()->nullable();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade')->nullable();
            $table->boolean('buy_ticket');
            $table->string('response')->nullable();
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
        Schema::dropIfExists('invitations');
    }
}
