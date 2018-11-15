<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formatos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preformato_id')->unsigned();
            $table->string('name');
            $table->longText('formato');
            $table->integer('state');
            $table->timestamps();

            $table->foreign('preformato_id')->references('id')->on('preformatos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formatos');
    }
}
