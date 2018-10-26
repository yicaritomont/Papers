<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionFormatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_format', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 45);
            $table->datetime('date');
            $table->integer('inspection_appointment_id')->unsigned();

            $table->foreign('inspection_appointment_id')
                ->references('id')->on('inspection_appointment')
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
        Schema::table('inspection_format', function (Blueprint $table) {
            //
        });
    }
}
