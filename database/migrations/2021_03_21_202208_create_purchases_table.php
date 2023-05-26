<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('send_from')->unsigned()->index();
            $table->foreign('send_from')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('ticket_id')->unsigned()->index();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->bigInteger('cart_id')->unsigned()->index();
            $table->foreign('cart_id')->references('id')->on('ticket_carts')->onDelete('cascade');
            $table->bigInteger('payment_id')->unsigned()->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
            
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
        Schema::dropIfExists('purchases');
    }
}
