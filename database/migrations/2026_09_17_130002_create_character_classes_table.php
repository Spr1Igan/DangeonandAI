<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('character_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_class_id')->constrained()->restrictOnDelete();
            $table->foreignId('class_version_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('level')->default(1);
            $table->timestamps();
            $table->unique(['character_id', 'game_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_classes');
    }
};
