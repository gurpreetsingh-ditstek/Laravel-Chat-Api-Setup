<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('local_message_id')->nullable();
            $table->string('message_id')->nullable();
            $table->Integer('reply_id')->default(0);
            $table->bigInteger('user_request_id')->unsigned()->index();
            $table->foreign('user_request_id')->references('id')->on('user_requests')->onDelete('cascade');
            $table->bigInteger('sender_id')->unsigned()->index();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('receiver_id')->unsigned()->index();
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('attachment')->nullable();
            $table->text('message')->nullable();
            $table->enum('type', ['text', 'attachment'])->default('text');
            $table->text('details')->nullable();
            $table->enum('is_read', ['0', '1'])->default('0');
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
        Schema::dropIfExists('messages');
    }
}
