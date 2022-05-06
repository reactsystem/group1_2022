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
        Schema::create('various_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('uuid')->unique();
            $table->tinyInteger('type');
            $table->date('date');
            $table->tinyInteger('status');
            $table->longText('reason');
            $table->longText('comment')->nullable();
            $table->string('related_id')->nullable();
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
        Schema::dropIfExists('requests');
    }
};
