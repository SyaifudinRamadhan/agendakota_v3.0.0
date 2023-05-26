<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pkg_id')->unsigned()->index();
            $table->foreign('pkg_id')->references('id')->on('package_pricings')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('photo');
            $table->string('phone', 15)->nullable();
            $table->text('bio')->nullable();
            $table->text('headline')->nullable();
            $table->string('instagram_profile')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('twitter_profile')->nullable();
            $table->tinyInteger('is_active');
            $table->integer('pkg_status');
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
        Schema::dropIfExists('users');
    }
}
