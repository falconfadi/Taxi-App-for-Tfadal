<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFourthWinOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fourth_win_offers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(null)->nullable();
            $table->integer('offer_id')->default(null)->nullable();
            $table->tinyInteger('num_of_trips')->default(0);


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
        Schema::dropIfExists('fourth_win_offers');
    }
}
