<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_teacher");
            $table->bigInteger("id_student");
            $table->bigInteger("id_attendance");
            $table->string("level");
            $table->string("unit");
            $table->string("last_exercise");
            $table->integer("score");
            $table->text("note")->nullable();
            $table->string("documentation")->nullable();
            $table->boolean("filled");
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
        Schema::dropIfExists('progress');
    }
}
