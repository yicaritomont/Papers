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
            $table->unsignedInteger('inspector_id')->nullable();
            $table->unsignedInteger('appointment_states_id')->default(1);
            $table->unsignedInteger('headquarters_id');
            $table->unsignedInteger('inspection_subtype_id');
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('format_id')->nullable();
            
            $table->dateTimeTz('request_date');
            $table->date('estimated_start_date');
            $table->date('estimated_end_date');
            $table->dateTimeTz('assignment_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('inspector_id')
                ->references('id')->on('inspectors')
                ->onDelete('cascade');
            $table->foreign('appointment_states_id')
                ->references('id')->on('appointment_states')
                ->onDelete('cascade');
            $table->foreign('headquarters_id')
                ->references('id')->on('headquarters')
                ->onDelete('cascade');
            $table->foreign('inspection_subtype_id')
                ->references('id')->on('inspection_subtypes')
                ->onDelete('cascade');
            $table->foreign('contract_id')
                ->references('id')->on('contracts')
                ->onDelete('cascade');
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');
            $table->foreign('format_id')
                ->references('id')->on('formats')
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
