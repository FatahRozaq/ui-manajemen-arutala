<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran_event', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->unsignedBigInteger('id_peserta');
            $table->unsignedBigInteger('id_agenda');
            $table->string('status_pembayaran', 255);
            $table->string('status_kehadiran', 255);
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->foreign('id_peserta')->references('id_pendaftar')->on('pendaftar');
            $table->foreign('id_agenda')->references('id_agenda')->on('agenda_pelatihan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_event');
    }
};
