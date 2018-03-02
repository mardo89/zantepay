<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEthAddressActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eth_address_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('operation_id', 100)->nullable();
            $table->string('status', 50)->default(\App\Models\DB\EthAddressAction::STATUS_IN_PROGRESS);
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('eth_address_actions');
    }
}
