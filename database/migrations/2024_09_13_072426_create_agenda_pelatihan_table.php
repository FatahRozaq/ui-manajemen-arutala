<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agenda_pelatihan', function (Blueprint $table) {
            $table->id('id_agenda');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('sesi', 25);
            $table->integer('investasi')->nullable();
            $table->string('investasi_info', 255)->nullable();
            $table->integer('diskon')->nullable();
            $table->string('status', 255);
            $table->string('link_mayar', 255);
            $table->string('id_mentor', 255)->nullable();
            $table->unsignedBigInteger('id_pelatihan');
            $table->integer('batch')->nullable();
            $table->date('start_pendaftaran')->nullable();
            $table->date('end_pendaftaran')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->foreign('id_pelatihan')->references('id_pelatihan')->on('pelatihan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_pelatihan');
    }
};
