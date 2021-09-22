<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventVenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'event_venue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->unsignedBigInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');

            $table->unsignedBigInteger('start_year')->nullable();
            $table->unsignedBigInteger('start_month')->nullable();
            $table->unsignedBigInteger('start_day')->nullable();

            $table->unsignedBigInteger('end_year')->nullable();
            $table->unsignedBigInteger('end_month')->nullable();
            $table->unsignedBigInteger('end_day')->nullable();

            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_venue');
    }
}
