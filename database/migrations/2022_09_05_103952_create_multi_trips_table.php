<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_trips', function (Blueprint $table) {
            $table->id();
            $table->integer('trip_id');
            $table->string('location_from');
            $table->string('location_to');
            $table->string('latitude_stop');
            $table->string('longitude_stop');
            $table->integer('order');

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
        Schema::dropIfExists('multi_trips');
    }
}
