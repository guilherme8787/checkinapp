<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaConvidado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convidado', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->bigInteger('id_mesa')->unsigned()->index();
            $table->foreign('id_mesa')->references('id')->on('mesa')->onDelete('cascade');
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
        Schema::dropIfExists('convidado');
    }
}
