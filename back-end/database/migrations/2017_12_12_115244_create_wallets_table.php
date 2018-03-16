<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->bigInteger('znx_amount')->default(0);
            $table->float('rs_bonus', 8, 2)->default(0);
            $table->float('rt_bonus', 8, 2)->default(0);
            $table->float('dc_bonus', 8, 2)->default(0);
            $table->string('btc_wallet', 150)->nullable();
            $table->string('eth_wallet', 150)->nullable();
            $table->string('znx_wallet', 150)->nullable();
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
        Schema::dropIfExists('wallets');
    }
}
