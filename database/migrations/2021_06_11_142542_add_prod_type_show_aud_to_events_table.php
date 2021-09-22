<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProdTypeShowAudToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('production_type_id')->nullable()->default(null)->after('language_id');
            $table->unsignedBigInteger('show_audience_id')->nullable()->default(null)->after('production_type_id');
        });
    }
}
