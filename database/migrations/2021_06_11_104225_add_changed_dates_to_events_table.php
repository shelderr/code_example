<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangedDatesToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {

            //Established days
            $table->unsignedBigInteger('established_year')->nullable();
            $table->unsignedBigInteger('established_month')->nullable();
            $table->unsignedBigInteger('established_day')->nullable();

            //Start date days
            $table->unsignedBigInteger('start_year')->nullable();
            $table->unsignedBigInteger('start_month')->nullable();
            $table->unsignedBigInteger('start_day')->nullable();

            //End date days
            $table->unsignedBigInteger('end_year')->nullable();
            $table->unsignedBigInteger('end_month')->nullable();
            $table->unsignedBigInteger('end_day')->nullable();
        });
    }

}
