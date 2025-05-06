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
        Schema::create('gpcs_codes_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gpcs_codes_id')->constrained('gpcs_codes');
            $table->foreignId('user_id')->constrained('users');
            $table->string('country_code');
            $table->string('first_part');
            $table->string('second_part');
            $table->string('gpcscode');
            $table->string('domain')->nullable();;
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('label')->nullable();
            $table->boolean('is_deleted');
            $table->integer('verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpcs_codes_histories');
    }
};
