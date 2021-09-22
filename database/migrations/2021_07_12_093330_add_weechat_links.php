<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeechatLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'events', function (Blueprint $table) {
                $table->renameColumn('whatsapp_url', 'tiktok_url');
        }
        );

        Schema::table(
            'persons', function (Blueprint $table) {
            $table->renameColumn('whatsapp_url', 'tiktok_url');
        }
        );

        Schema::table(
            'collectives', function (Blueprint $table) {
            $table->renameColumn('whatsapp_url', 'tiktok_url');
        }
        );

        Schema::table(
            'venues', function (Blueprint $table) {
            $table->renameColumn('wechat_url', 'tiktok_url');
        }
        );

    }

}
