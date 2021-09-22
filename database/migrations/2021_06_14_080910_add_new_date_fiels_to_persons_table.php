<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewDateFielsToPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            //Birth date
            $table->unsignedBigInteger('birth_year')->nullable()->after('image');
            $table->unsignedBigInteger('birth_month')->nullable()->after('image');
            $table->unsignedBigInteger('birth_day')->nullable()->after('image');

            //Death Date
            $table->unsignedBigInteger('death_year')->nullable()->after('image');
            $table->unsignedBigInteger('death_month')->nullable()->after('image');
            $table->unsignedBigInteger('death_day')->nullable()->after('image');
        });
    }
}
