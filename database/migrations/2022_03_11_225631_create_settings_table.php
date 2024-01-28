<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone',25)->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('ios_app_url')->nullable();
            $table->string('ios_version')->nullable();
            $table->string('android_version')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
