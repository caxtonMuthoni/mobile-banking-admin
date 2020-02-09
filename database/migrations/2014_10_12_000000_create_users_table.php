<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('NationalID');
            $table->string('FirstName');
            $table->string('MiddleName');
            $table->string('LastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('PhoneNumber');
            $table->boolean('PhoneStatus')->default(false);
            $table->string('City');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
