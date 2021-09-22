<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardsableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'awardsable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('awards_id');
            $table->foreign('awards_id')
                ->references('id')
                ->on('awards')
                ->onDelete('cascade');

            $table->unsignedBigInteger('awardsable_id');
            $table->string('awardsable_type');
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
        Schema::dropIfExists('awardsable');
    }
}
