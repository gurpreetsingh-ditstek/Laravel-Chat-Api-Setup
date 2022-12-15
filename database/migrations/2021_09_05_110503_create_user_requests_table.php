<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequestsTable extends Migration
{
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                Schema::create('user_requests', function (Blueprint $table) {
                        $table->bigIncrements('id');
                        $table->unsignedBigInteger("user_id")->index()->nullable();
                        $table->string('unique_code')->nullable();
                        $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
                        $table->unsignedBigInteger("area_id")->nullable();
                        $table->unsignedBigInteger("category_id")->nullable();
                        $table->unsignedBigInteger("sub_category_id")->nullable();
                        $table->unsignedBigInteger("treatment_id")->nullable();
                        $table->foreign("area_id")
                                ->references("id")
                                ->on("areas")
                                ->onDelete("cascade");
                        $table->foreign("category_id")
                                ->references("id")
                                ->on("categories")
                                ->onDelete("cascade");
                        $table->foreign("sub_category_id")
                                ->references("id")
                                ->on("sub_categories")
                                ->onDelete("cascade");
                        $table->foreign("treatment_id")
                                ->references("id")
                                ->on("treatments")
                                ->onDelete("cascade");
                        $table->string('fluency')->nullable();
                        $table->string("visitor_local")->nullable();
                        $table->string('preferred')->nullable();
                        $table->string("time_preference")->nullable();
                        $table->json("language_preference")->nullable();
                        $table->string("preferred_language")->nullable();
                        $table->integer('expire_in')->nullable()->unsigned();
                        $table->dateTime('posted_on')->nullable();
                        $table->dateTime('expired_date')->nullable();
                        $table->longText("personal_note")->nullable();
                        $table->longText("description")->nullable();
                        $table->integer('previous_expire_in')->nullable()->unsigned();
                        $table->longText("previous_personal_note")->nullable();
                        $table->longText("previous_description")->nullable();
                        $table->enum("status", ["1", "2", "3", "4", "5", "6", "7"])
                                ->comment("1=>Draft,2=>Active,3=>Order Placed ,4=>Order Cancelled ,5=>Order Phase 1 Completed ,6=>Order Completed ,7=>Expired");
                        $table->enum("payment_status", ["1", "2", "3", "4", "5"])
                                ->default("1")
                                ->comment("1=>Partial Paid ,2=>Phase 1 Fully Paid, 3=>Fully Paid ,4=>Pending, 5=>Refund Resolved");
                        $table->longText("comments")->nullable();
                        $table->boolean("terms_of_use")->default(1)->comment("1=>For acceptance of terms and policies");
                        $table->string('feedback')->nullable();
                        $table->string('user_experience')->nullable();
                        $table->timestamp('updated_on')->nullable();
                        $table->timestamps();
                        $table->softDeletes();
                });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
                Schema::dropIfExists('user_requests');
        }
}
