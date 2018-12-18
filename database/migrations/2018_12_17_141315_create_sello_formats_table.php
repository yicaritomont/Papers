<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelloFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sello_formats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_formato');
            $table->unsignedInteger('id_usuario');
            $table->string('id_sello');
            $table->longText('sello');
            $table->timestamps();

            $table->foreign('id_formato')->references('id')->on('formats')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sello_formats');
    }
}
