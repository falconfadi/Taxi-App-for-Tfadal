<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_users', function (Blueprint $table) {
            $table->id();
            $table->float('avg_reach_time');
            $table->float('sum_money_paid');
            $table->float('sum_trip_cancelled');
            $table->float('sum_trip_acheived');
            $table->integer('user_id');
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
        Schema::dropIfExists('kpi_users');
    }
}
