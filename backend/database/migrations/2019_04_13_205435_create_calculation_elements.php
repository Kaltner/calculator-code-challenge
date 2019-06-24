<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculation_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('calc_id');
            $table->string('element', 100);

            $table->foreign('calc_id')->references('id')->on('calculations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculation_elements');
    }
}
