<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addCollectiveIdYearsToEventPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_person', function (Blueprint $table) {
            $table->unsignedBigInteger('persons_id')->nullable()->default(null)->change();
            $table->unsignedBigInteger('collective_id')->nullable()->default(null)->after('persons_id');
            $table->json('years')->nullable()->default(null)->after('field_name');
        });
    }
}
