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
            $table->string('burial_data_id');
            $table->string('name');
            $table->string('nik');
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('regency_of_birth')->nullable();
            $table->text('address');
            $table->integer('village_id');
            $table->tinyInteger('rt');
            $table->tinyInteger('rw');
            $table->string('reporters_name')->nullable();
            $table->string('reporters_nik')->nullable();
            $table->string('reporters_address')->nullable();
            $table->string('reporters_relationship')->nullable();
            $table->string('reporters_phone')->nullable();
            $table->date('date_of_death')->nullable();
            $table->integer('regency_of_death')->nullable();
            $table->date('buried_date')->nullable();
            $table->integer('burial_type_id')->nullable();
            $table->integer('grave_block')->nullable();
            $table->string('grave_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->text('grave_photo')->nullable();
            $table->text('application_letter_photo')->nullable();
            $table->text('ktp_corpse_photo')->nullable();
            $table->text('cover_letter_photo')->nullable();
            $table->text('reporter_ktp_photo')->nullable();
            $table->text('reporter_kk_photo')->nullable();
            $table->text('letter_of_hospital_statement_photo')->nullable();
            $table->string('guardian_name')->nullable();
            $table->integer('guardian_phone')->nullable();
            $table->integer('tpu_id')->nullable();
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
