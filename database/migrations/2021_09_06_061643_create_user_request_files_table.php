<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_request_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_request_id")->index()->nullable();
            $table->string('name')->nullable();
            $table->foreign("user_request_id")->references("id")->on("user_requests");
            $table->string('document_url')->nullable();
            \App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_request_files');
    }
}
