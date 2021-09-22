<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slogan');
            $table->string('type');
            $table->boolean('is_active');
            $table->string('poster');
            $table->string('poster_author')->nullable();
            $table->string('poster_source')->nullable();
            $table->string('poster_permission')->nullable();
            $table->string('company_name');
            $table->longText('description');
            $table->string('tickets_url')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->unsignedBigInteger('producer_id');
            $table->unsignedBigInteger('president_id');
            $table->unsignedBigInteger('language_id');

            $table->date('year_established');
            $table->boolean('is_television');
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
        Schema::dropIfExists('events');
    }
}
