<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreformatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preformatos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inspection_subtype_id')->unsigned();
            $table->string('name');
            $table->longText('preformato');
            $table->integer('state');
            $table->timestamps();

            $table->foreign('inspection_subtype_id')->references('id')->on('inspection_subtypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preformatos');
    }
}
