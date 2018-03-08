<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantCoinsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_coins_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 150);
            $table->integer('amount');
            $table->string('type', '50');
            $table->string('operation_id', 100)->nullable();
            $table->integer('status')->default(\App\Models\DB\GrantCoinsTransaction::STATUS_IN_PROGRESS);
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
        Schema::dropIfExists('grant_coins_transactions');
    }
}
