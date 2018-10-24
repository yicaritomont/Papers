<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarters', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('client_id')->unsigned();
            $table->Integer('cities_id')->unsigned();
            $table->string('name', 40);
            $table->string('address', 60);
            $table->integer('status');
            $table->string('slug', 40)->unique();

            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');

            $table->foreign('cities_id')
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
        Schema::dropIfExists('headquarters');
    }
}
