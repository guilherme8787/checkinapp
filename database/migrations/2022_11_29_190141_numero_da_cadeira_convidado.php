<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NumeroDaCadeiraConvidado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convidado', function (Blueprint $table) {
            $table->string('numero_da_cadeira')->nullable();
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
            $table->dropColumn('numero_da_cadeira');
        });
    }
}
