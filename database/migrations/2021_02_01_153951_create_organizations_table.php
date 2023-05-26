<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('slug', 125);
            $table->string('name');
            $table->string('logo');
            $table->string('type',212);
            $table->string('interest',212);
            $table->string('email',212);
            $table->string('no_telepon',22);
            $table->string('instagram',212)->nullable();
            $table->string('linked',212)->nullable();
            $table->string('twitter',212)->nullable();
            $table->string('website',212)->nullable();
            $table->text('description');
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
        Schema::dropIfExists('organizations');
    }
}
