<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_has_privilegio', function (Blueprint $table) {
            $table->bigInteger('users_id')->unsigned();
            $table->bigInteger('privilegio_id')->unsigned();
            $table->timestamps();
            $table->foreign('users_id')->references('id')
                                       ->on('users')
                                       ->onCascade('delete');
            $table->foreign('privilegio_id')->references('id')
                                       ->on('privilegio')
                                       ->onCascade('delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_has_privilegio');
    }
};