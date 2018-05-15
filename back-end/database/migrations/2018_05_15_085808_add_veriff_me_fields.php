<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVeriffMeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->string('session_id')->after('address_decline_reason');
            $table->text('session_token')->after('session_id');
            $table->string('response_status')->after('session_token');
            $table->string('response_code')->after('response_status');
            $table->string('fail_reason')->after('response_code');
            $table->integer('status')->default(0)->after('fail_reason');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->dropColumn('session_token');
            $table->dropColumn('response_status');
            $table->dropColumn('response_code');
            $table->dropColumn('fail_reason');
            $table->dropColumn('status');
        });
    }
}
