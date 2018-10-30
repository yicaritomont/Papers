<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('number_tride')->nullable(false)->default(5);
            $table->string('picture')->nullable(true);
            $table->integer('status')->nullable(false)->default(1);
            $table->rememberToken();
            $table->string('last_session_id')->nullable(true);
            $table->string('last_ip_session')->nullable(true);
            $table->string('login_attempts', 1)->nullable(true);
            $table->dateTime('last_login_attempt')->nullable(true);
            $table->dateTime('last_login')->nullable(true);
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
        Schema::dropIfExists('users');
    }
}
