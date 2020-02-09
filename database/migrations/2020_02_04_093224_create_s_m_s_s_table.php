<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSMSSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_m_s_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userid');
            $table->text('Message');
            $table->string('phone');
            $table->float('amount',8,4);
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
        Schema::dropIfExists('s_m_s_s');
    }
}
