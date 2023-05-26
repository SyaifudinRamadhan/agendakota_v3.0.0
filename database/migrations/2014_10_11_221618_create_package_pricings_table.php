<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('organizer_count');
            $table->integer('event_same_time');
            $table->double('ticket_commission');
            $table->integer('session_count');
            $table->boolean('custom_link');
            $table->integer('sponsor_count');
            $table->integer('exhibitor_count');
            $table->integer('partner_media_count');
            $table->boolean('report_download');
            $table->integer('max_attachment');
            $table->integer('price');
            $table->integer('price_in_year');
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
        Schema::dropIfExists('package_pricings');
    }
}
