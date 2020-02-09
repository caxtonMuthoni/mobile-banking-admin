<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('userId');
            $table->string('title');
            $table->text('description');
            $table->string('project_image')->default("default.png");
            $table->float('amountBorrowed',8,2);
            $table->float('balance',8,2);
            $table->boolean('status')->default(true);
            $table->boolean('paymentstatus')->default(false);
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
        Schema::dropIfExists('borrows');
    }
}
