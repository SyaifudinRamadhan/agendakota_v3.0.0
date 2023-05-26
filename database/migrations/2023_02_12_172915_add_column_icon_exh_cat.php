<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIconExhCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('booth_categories', function (Blueprint $table) {
            $table->string('icon')->default('default_icon.png');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('booth_categories', function (Blueprint $table) {
            $table->string('icon')->default('default_icon.png');
        });
    }
}
