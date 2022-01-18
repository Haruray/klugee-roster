<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('official_id')->unique()->nullable();
            $table->string('name');
            $table->string('nickname');
            $table->string('birthplace');
            $table->date("birthdate");
            $table->string("school_name");
            $table->string("parent");
            $table->string("parent_name");
            $table->string("parent_contact");
            $table->string("email")->unique()->nullable();
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
        Schema::dropIfExists('students');
    }
}
