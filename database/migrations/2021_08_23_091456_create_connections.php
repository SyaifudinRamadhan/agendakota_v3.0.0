<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index();         
            $table->bigInteger('connection_id')->unsigned()->index();            
            $table->bigInteger('from_id');
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
        Schema::dropIfExists('connections');
    }
}
