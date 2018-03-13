<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBonusFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('rs_bonus');
            $table->dropColumn('rt_bonus');
            $table->dropColumn('dc_bonus');

//            $table->bigInteger('znx_amount')->change();
            $table->integer('referral_bonus')->default(0)->after('znx_amount');
            $table->integer('debit_card_bonus')->default(0)->after('referral_bonus');
            $table->float('commission_bonus', 30, 8)->default(0)->after('debit_card_bonus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('referral_bonus');
            $table->dropColumn('debit_card_bonus');
            $table->dropColumn('commission_bonus');

            $table->float('znx_amount', 8, 2)->change();
            $table->float('rs_bonus', 8, 2)->default(0);
            $table->float('rt_bonus', 8, 2)->default(0);
            $table->float('dc_bonus', 8, 2)->default(0);
        });
    }
}
