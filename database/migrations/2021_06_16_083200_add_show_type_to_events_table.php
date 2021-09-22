<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowTypeToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('year_established');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->unsignedBigInteger('show_type_id')->nullable()->after('show_audience_id');
        });
    }
}
