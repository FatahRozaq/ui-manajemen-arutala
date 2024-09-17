<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id('id_pendaftar');
            $table->string('nama', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('no_kontak', 25);
            $table->string('aktivitas', 15)->nullable();
            $table->string('nama_instansi', 50)->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('kab_kota', 50)->nullable();
            $table->string('linkedin', 50)->nullable();
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->timestamp('modified_time')->nullable();
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftar');
    }
};
