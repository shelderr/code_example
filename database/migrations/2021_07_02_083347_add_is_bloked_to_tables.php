<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBlokedToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });

        Schema::table('collectives', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
        });
    }

}
