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
            $table->integer('company_id')->unsigned();
            $table->string('name');
            $table->longText('header');
            $table->longText('format');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('inspection_subtype_id')
              ->references('id')
              ->on('inspection_subtypes')
              ->onDelete('cascade');

            $table->foreign('company_id')
              ->references('id')
              ->on('companies')
              ->onDelete('cascade');
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
