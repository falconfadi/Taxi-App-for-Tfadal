<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_kpis', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('hours');
            $table->mediumInteger('completed_trips');
            $table->unsignedFloat('whole_distance');
            $table->Integer('money');
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
        Schema::dropIfExists('daily_kpis');
    }
}
