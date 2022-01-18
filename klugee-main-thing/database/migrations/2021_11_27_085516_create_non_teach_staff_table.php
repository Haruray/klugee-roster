<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonTeachStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_teach_staff', function (Blueprint $table) {
            $table->id();
            $table->string("official_id")->unique()->nullable();
            $table->string("name");
            $table->bigInteger("nik");
            $table->string("birthplace");
            $table->date("birthdate");
            $table->string("address");
            $table->string("phone_contact");
            $table->string("emergency_contact")->nullable();
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
        Schema::dropIfExists('non_teach_staff');
    }
}
