<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_class_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->text('description')->nullable();
            $table->text('rules_text')->nullable();
            $table->json('mechanics')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();
            $table->unique(['game_class_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_versions');
    }
};
