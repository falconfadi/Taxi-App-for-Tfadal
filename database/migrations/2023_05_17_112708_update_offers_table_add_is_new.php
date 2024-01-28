<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOffersTableAddIsNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function($table) {
            $table->dropColumn('is_customized');
            $table->dropColumn('kilometers');
            $table->dropColumn('price');
            $table->integer('discount')->default(0);
            $table->integer('amount')->default(0);
            $table->tinyInteger('is_new_client')->default(0);
            $table->integer('num_of_trips')->default(0);
            $table->tinyInteger('status')->default(0);
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
    }
}
