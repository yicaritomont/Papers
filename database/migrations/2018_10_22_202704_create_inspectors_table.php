<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspectors', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('identification');
            $table->string('phone');
            $table->string('addres');            
            $table->integer('status')->default('1');
            $table->unsignedInteger('profession_id');
            $table->unsignedInteger('inspector_type_id');
            $table->unsignedInteger('user_id');
             
             $table->foreign('profession_id')
                ->references('id')->on('professions')
                ->onDelete('cascade');
            $table->foreign('inspector_type_id')
                ->references('id')->on('inspector_types')
                ->onDelete('cascade');            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('inspectors');
    }
}
