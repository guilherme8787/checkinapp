<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdConvidadoAcompanhante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convidado', function (Blueprint $table) {
            $table->integer('id_convidado_acompanhante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convidado', function (Blueprint $table) {
            $table->dropColumn('id_convidado_acompanhante');
        });
    }
}
