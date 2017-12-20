<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('id_documents_status')->default(\App\Models\Verification::DOCUMENTS_NOT_UPLOADED);
            $table->string('id_decline_reason', 150)->default('');
            $table->tinyInteger('address_documents_status')->default(\App\Models\Verification::DOCUMENTS_NOT_UPLOADED);
            $table->string('address_decline_reason', 150)->default('');
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
        Schema::dropIfExists('verifications');
    }
}
