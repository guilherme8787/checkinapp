<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Estatisticas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estatisticas', function (Blueprint $table) {
            $table->id();
            $table->string('emailAddress');
            $table->string('job_title');
            $table->string('state');
            $table->string('city');
            $table->string('country');
            $table->string('event_number');
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
        Schema::dropIfExists('estatisticas');
    }
}
