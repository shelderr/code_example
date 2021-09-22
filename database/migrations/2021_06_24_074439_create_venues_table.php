<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->string('alternative_names')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('city')->nullable();
            $table->string('street_address')->nullable();
            $table->string('coordinates')->nullable();
            $table->longText('description')->nullable();
            $table->integer('opening_year')->nullable();
            $table->integer('opening_month')->nullable();
            $table->integer('opening_day')->nullable();
            $table->string('seating_capacity')->nullable();
            $table->string('image')->nullable();
            $table->string('image_author')->nullable();
            $table->string('image_source')->nullable();
            $table->string('image_permission')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('is_active')->nullable();

            //social links
            $table->string('web_url')->nullable();
            $table->string('wikipedia_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('vk_url')->nullable();
            $table->string('wechat_url')->nullable();
            $table->string('telegram_url')->nullable();

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
        Schema::dropIfExists('venues');
    }
}
