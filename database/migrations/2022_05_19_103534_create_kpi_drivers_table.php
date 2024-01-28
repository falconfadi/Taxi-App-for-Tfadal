<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_drivers', function (Blueprint $table) {
            $table->id();
            $table->float('avg_reach_time');
            $table->float('sum_money_acheived');
            $table->integer('sum_trip_cancelled');
            $table->integer('sum_trip_acheived');
            $table->integer('driver_id');
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
        Schema::dropIfExists('kpi_drivers');
    }
}
