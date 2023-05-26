<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempTicketSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_ticket_shares', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_id')->unsigned()->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->bigInteger('purchase_id')->unsigned()->index();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->string('share_to');
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
        Schema::dropIfExists('temp_ticket_shares');
    }
}
