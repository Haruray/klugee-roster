<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachPresencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teach_presences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_teacher")->nullable();
            $table->bigInteger("id_non_teach_staff")->nullable();
            $table->bigInteger("id_attendance");
            $table->date("date");
            $table->boolean("approved");
            $table->boolean("fee_paid");
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
        Schema::dropIfExists('teach_presences');
    }
}
