<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userId');
            $table->string('loanId');
            $table->string('loanType');
            $table->string('borrowedAmount');
            $table->string('interest');
            $table->string('period');
            $table->string('installment');
            $table->timestamp('nextPayment')->nullable();
            $table->timestamp('dueDate')->nullable();
            $table->string('totalRepayable');
            $table->string('paidAmount')->default('0');
            $table->string('guarantorAmount');
            $table->string('guaranteedAmount')->default('0');
            $table->boolean('guaranteeStatus')->default(false);
            $table->boolean('isProcessed')->default(false);
            $table->string('status')->default('pending');
            
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
        Schema::dropIfExists('loans');
    }
}
