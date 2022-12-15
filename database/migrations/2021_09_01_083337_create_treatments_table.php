<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title")->nullable();
            $table->longText("description")->nullable();
            $table->string("anesthesia")->nullable();
            $table->string("treatment_time")->nullable();
            $table->string("inpatient_period")->nullable();
            $table->string("number_of_appointments")->nullable();
            $table->string("recovery_period")->nullable();
            $table->string("invasiveness")->nullable();
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
        Schema::dropIfExists('treatments');
    }
}