<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('post_code', 10)->nullable();
            $table->string('passport_id', 50)->nullable();
            $table->date('passport_expiration_date')->nullable();
            $table->date('birth_date')->nullable();
            $table->bigInteger('birth_country_id')->nullable();
            $table->string('eth_wallet', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
