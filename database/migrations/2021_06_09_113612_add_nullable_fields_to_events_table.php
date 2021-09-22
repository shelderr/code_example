<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableFieldsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('slogan')->nullable()->change();
            $table->string('type')->nullable()->change();
            $table->boolean('is_active')->nullable()->change();
            $table->string('poster')->nullable()->change();
            $table->string('company_name')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->dateTime('start_date')->nullable()->change();
            $table->dateTime('end_date')->nullable()->change();

            $table->unsignedBigInteger('producer_id')->nullable()->change();
            $table->unsignedBigInteger('president_id')->nullable()->change();
            $table->unsignedBigInteger('language_id')->nullable()->change();

            $table->date('year_established')->nullable()->change();
            $table->boolean('is_television')->nullable()->change();
        });
    }
}
