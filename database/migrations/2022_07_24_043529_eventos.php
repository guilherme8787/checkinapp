<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Eventos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('id_guest_list');
            $table->string('id_visitor_list');
            $table->string('event_description');
            $table->string('event_img')->nullable(true);
            $table->string('color')->nullable(true);
            $table->string('font_color')->nullable(true);
            $table->date('credential_expiration_date')->nullable(true);
            $table->date('registration_link')->nullable(true);
            $table->string('url_inscricao')->nullable(true);
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
        Schema::dropIfExists('eventos');
    }
}
