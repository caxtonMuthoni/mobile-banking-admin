<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('UserId');
            $table->string('Avatar')->default('default.png');
            $table->boolean('EmploymentStatus')->default(0);
            $table->string('Company')->default('None');
            $table->string('Occupation');
            $table->string('AnualIncome');
            $table->string('MonthlyIncome');
            $table->string('Bio');
            $table->string('EducationLevel');
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
        Schema::dropIfExists('profiles');
    }
}
