<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id('id_pelatihan');
            $table->string('nama_pelatihan', 255);
            $table->string('gambar_pelatihan', 255);
            $table->string('deskripsi', 255);
            $table->string('benefit', 255);
            $table->string('materi', 255);
            $table->boolean('is_deleted')->default(false);
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_time')->nullable();
            $table->string('modified_by', 255)->nullable();
            $table->timestamp('modified_time')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihan');
    }
};
