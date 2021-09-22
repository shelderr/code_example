<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventActsPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_acts_person', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_act_id');
            $table->foreign('event_act_id')->references('id')->on('event_acts')->onDelete('cascade');

            $table->unsignedBigInteger('persons_id');
            $table->foreign('persons_id')->references('id')->on('persons')->onDelete('cascade');
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
        Schema::dropIfExists('event_acts_person');
    }
}
