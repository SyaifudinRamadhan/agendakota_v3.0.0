<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_discounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id')->unsigned()->index();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->string('discount_type');
            $table->string('discount_amount');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->bigInteger('minimum_transaction');
            $table->integer('quantity');
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
        Schema::dropIfExists('ticket_discounts');
    }
}
