<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignaFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signa_formats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_formato');
            $table->unsignedInteger('id_usuario');
            $table->string('id_firma');
            $table->longText('base64');
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
        Schema::dropIfExists('signa_formats');
    }
}
