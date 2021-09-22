<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_role', function (Blueprint $table) {
            $table->unsignedBigInteger('persons_id');
            $table->unsignedBigInteger('roles_id');
            $table->timestamps();
        });

       // Artisan::call('db:seed', ['--class' => \Database\Seeders\PersonalitySeeder::class]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_role');
    }
}
