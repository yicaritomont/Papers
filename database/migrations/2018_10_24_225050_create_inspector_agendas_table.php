<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspector_agendas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inspector_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('slug', 40)->unique()->nullable();

            $table->foreign('inspector_id')
                ->references('id')->on('inspectors')
                ->onDelete('cascade');

            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('cascade');
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
        Schema::dropIfExists('inspector_agendas');
    }
}
