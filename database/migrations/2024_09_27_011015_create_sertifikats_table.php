<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id('id_sertifikat');
            $table->unsignedBigInteger('id_pendaftar');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->string('file_sertifikat', 255)->nullable();
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->string('sertifikat_kehadiran', 255);
            $table->string('certificate_number_kompetensi', 255)->nullable();
            $table->string('certificate_number_kehadiran', 255)->nullable();
            $table->string('qr_kompetensi', 255)->nullable();
            $table->string('qr_kehadiran', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_pendaftar')->references('id_pendaftar')->on('pendaftar');
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
