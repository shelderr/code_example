<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullUrlToLinksDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links_details', function (Blueprint $table) {
            Schema::table('links', function (Blueprint $table) {
                $table->string('url')->nullable()->change();
            });

            Schema::table('critics', function (Blueprint $table) {
                $table->string('url')->nullable()->change();
            });
        });
    }

}
