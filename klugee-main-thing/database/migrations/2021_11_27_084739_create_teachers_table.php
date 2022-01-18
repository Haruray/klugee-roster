<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('official_id')->unique()->nullable();
            $table->string("name");
            $table->string("nickname");
            $table->string("birthplace");
            $table->date("birthdate");
            $table->string("phone_contact");
            $table->bigInteger("nik");
            $table->string("institution_name")->nullable();
            $table->date("join_date");
            $table->boolean("status");
            $table->string("photo")->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
