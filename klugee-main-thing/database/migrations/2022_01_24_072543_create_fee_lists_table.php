<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_lists', function (Blueprint $table) {
            $table->id();
            $table->string("program");
            $table->string("level");
            $table->bigInteger("nominal_exclusive");
            $table->bigInteger("nominal_semiprivate");
            $table->bigInteger("nominal_school");
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
        Schema::dropIfExists('fee_lists');
    }
}
