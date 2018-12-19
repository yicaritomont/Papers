<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_formato');
            $table->unsignedInteger('id_usuario');
            $table->longText('hash');
            $table->longText('base64');
            $table->longText('tx_hash');
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
        Schema::dropIfExists('block_info');
    }
}
