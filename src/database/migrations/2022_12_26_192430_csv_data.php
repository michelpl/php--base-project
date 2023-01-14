<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->id();
            $table->string('csv_file_hash')->index('csv_file_hash')->unique('csv_file_hash');
            $table->string('csv_filename');
            $table->json('csv_header');
            $table->json('csv_data');
            $table->enum('status', ['pending', 'migrated'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('', function (Blueprint $table) {
            Schema::dropIfExists('csv_data');
        });
    }
};