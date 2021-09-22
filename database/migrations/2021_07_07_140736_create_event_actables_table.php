<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventActablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'event_actables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_act_id');
            $table->foreign('event_act_id')
                ->references('id')
                ->on('event_acts')
                ->onDelete('cascade');

            $table->unsignedBigInteger('event_actable_id');
            $table->string('event_actable_type');
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
        Schema::dropIfExists('event_actables');
    }
}
