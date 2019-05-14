<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirtableConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtable_connections', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('api_key');
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('airtable_connections', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('airtable_connections', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
        Schema::dropIfExists('airtable_connections');
    }
}
