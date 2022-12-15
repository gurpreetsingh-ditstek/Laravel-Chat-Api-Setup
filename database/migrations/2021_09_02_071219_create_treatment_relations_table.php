<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('treatment_relations');
    }
}
