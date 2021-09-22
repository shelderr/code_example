<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeingKeyFromCollectiveTrailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collective_trailers', function (Blueprint $table) {
            $table->dropForeign(['collective_id']);
        });
    }
}
