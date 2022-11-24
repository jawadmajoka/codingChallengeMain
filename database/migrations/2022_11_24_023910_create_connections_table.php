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
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->integer('requester_user_id')->index('requester_user_id');
            $table->foreign('requester_user_id')->references('id')->on('users');
            $table->integer('recipient_user_id')->index('recipient_user_id');
            $table->foreign('recipient_user_id')->references('id')->on('users');
            $table->integer('status')->index('status')->default(0);
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
        Schema::dropIfExists('connections');
    }
};
