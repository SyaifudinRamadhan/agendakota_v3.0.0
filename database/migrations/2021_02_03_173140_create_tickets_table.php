<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('session_id')->unsigned()->index();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            // $table->bigInteger('end_session_id')->unsigned()->index();
            // $table->foreign('end_session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->string('name');
            $table->string('description');
            $table->integer('type_price');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('start_quantity');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('deleted');
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
        Schema::dropIfExists('tickets');
    }
}
