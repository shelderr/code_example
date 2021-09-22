<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWikidataFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('wikidata_url')->nullable();

        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('wikidata_url')->nullable();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->string('wikidata_url')->nullable();
        });

        Schema::table('collectives', function (Blueprint $table) {
            $table->string('wikidata_url')->nullable();
        });
    }

}
