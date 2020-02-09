<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('TransactionType');
            $table->string('TransID');
            $table->double('TransAmount',8,2);
            $table->string('Account');
            $table->string('MSISDN');
            $table->string('FirstName');
            $table->string('MiddleName');
            $table->string('LastName');
            $table->double('OrgAccountBalance',8,2);
            $table->double('CrtAccountBalance',8,2);
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
        Schema::dropIfExists('transactions');
    }
}
