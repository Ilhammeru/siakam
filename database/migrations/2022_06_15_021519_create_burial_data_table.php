<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('burial_data', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->integer('nik');
            $table->text('address');
            $table->integer('village_id');
            $table->tinyInteger('rt');
            $table->tinyInteger('rw');
            $table->date('birth_date')->nullable();
            $table->integer('regency_of_birth')->nullable();
            $table->date('date_of_death')->nullable();
            $table->integer('regency_of_death')->nullable();
            $table->date('buried_date')->nullable();
            $table->string('reporters_name')->nullable();
            $table->integer('reporters_nik')->nullable();
            $table->string('guardian_name')->nullable();
            $table->integer('guardian_phone')->nullable();
            $table->integer('tpu_id')->nullable();
            $table->integer('grave_block')->nullable();
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
        Schema::dropIfExists('burial_data');
    }
};
