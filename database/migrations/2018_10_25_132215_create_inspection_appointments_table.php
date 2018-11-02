<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inspector_id');
            $table->unsignedInteger('appointment_states_id')->default(1);
            $table->unsignedInteger('appointment_location_id');
            $table->unsignedInteger('inspection_subtype_id');
            
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->foreign('inspector_id')
                ->references('id')->on('inspectors')
                ->onDelete('cascade');
            $table->foreign('appointment_states_id')
                ->references('id')->on('appointment_states')
                ->onDelete('cascade');
            $table->foreign('appointment_location_id')
                ->references('id')->on('appointment_locations')
                ->onDelete('cascade');
            $table->foreign('inspection_subtype_id')
                ->references('id')->on('inspection_subtypes')
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
        Schema::dropIfExists('inspection_appointments');
    }
}
