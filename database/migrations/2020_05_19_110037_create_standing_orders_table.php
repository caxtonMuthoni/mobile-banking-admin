<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standing_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userId');
            $table->string('accountId');
            $table->string('amount');
            $table->string('duration');
            $table->string('destinationId');
            $table->timeStamp('nextOrder')->nullble();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('standing_orders');
    }
}
