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
        Schema::create('service_monitoring', function (Blueprint $table) {
            $table->id();
            $table->string('container_number');
            $table->integer('maintainer_id');
            $table->float('temperature')->nullable();
            $table->boolean('is_upnormal')->nullable();
            $table->string('upnormal_code')->nullable();
            $table->text('description')->nullable();
            $table->text('photo')->nullable();
            $table->date('date_check');
            $table->time('time_check');
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
        Schema::dropIfExists('service_monitoring');
    }
};
