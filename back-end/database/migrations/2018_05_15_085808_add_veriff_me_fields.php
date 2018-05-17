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
            $table->string('session_id')->after('address_decline_reason')->default('');
            $table->string('response_status')->after('session_id')->default('');
            $table->string('response_code')->after('response_status')->default('');
            $table->string('fail_reason')->after('response_code')->default('');
            $table->integer('status')->after('fail_reason')->default(0);
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
            $table->dropColumn('response_status');
            $table->dropColumn('response_code');
            $table->dropColumn('fail_reason');
            $table->dropColumn('status');
        });
    }
}
