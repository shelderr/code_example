<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFolderAbleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'folderable', function (Blueprint $table) {

            $table->unsignedBigInteger('bookmark_folder_id');
            $table->foreign('bookmark_folder_id')
                ->references('id')
                ->on('bookmark_folder')
                ->onDelete('cascade');

            $table->unsignedBigInteger('folderable_id');
            $table->string('folderable_type');

            $table->timestamps();
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
        Schema::dropIfExists('bookmarks');
    }
}
