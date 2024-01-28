<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCompensationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_compensation', function (Blueprint $table) {
            $table->id();
            $table->string('latitude',25);
            $table->string('longitude',25);
            $table->integer('driver_id');
            $table->integer('trip_id');
            $table->integer('distance');
            $table->integer('compensation');
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
        Schema::dropIfExists('driver_compensation');
    }
}
