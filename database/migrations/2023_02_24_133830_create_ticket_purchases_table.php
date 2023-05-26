<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('send_from')->unsigned()->index();
            $table->foreign('send_from')->references('id')->on('users');
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events');
            $table->bigInteger('ticket_id')->unsigned()->index();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->bigInteger('payment_id')->unsigned()->index()->nullable();
            $table->foreign('payment_id')->references('id')->on('ticket_payments');
            $table->string('code');
            $table->bigInteger('price');
            $table->tinyInteger('checked_in');
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
        Schema::dropIfExists('ticket_purchases');
    }
}
