<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWithdrawalEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_withdrawal_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('organization_id')->unsigned()->index();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->bigInteger('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('account_id')->unsigned()->nullable()->index();
            $table->foreign('account_id')->references('id')->on('billing_accounts')->onDelete('set null');
            $table->string('bank_name');
            $table->string('account_number');
            $table->integer('nominal');
            $table->string('status', 100);
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
        Schema::dropIfExists('user_withdrawal_events');
    }
}
