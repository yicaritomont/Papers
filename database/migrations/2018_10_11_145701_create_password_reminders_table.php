<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('password_reminders', function(Blueprint $table)
		{
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
			$table->string('email')->index();
			$table->string('token')->index();
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
        //
        Schema::drop('password_reminders');
    }
}
