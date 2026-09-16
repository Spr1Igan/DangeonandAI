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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 120)->nullable();
            $table->string('slug', 150)->nullable();
            $table->text('description')->nullable();
            $table->integer('hp_max')->nullable();
            $table->integer('hp_current')->nullable();
            $table->string('system', 50)->default('dnd5e');
            $table->string('ruleset_version', 50)->nullable(); //Например 5e-2014, 5e-2024, custom
            $table->unsignedBigInteger('current_revision')->default(1);
            $table->enum('status', [
                'draft',
                'active',
                'archived',
            ])->default('draft');
            $table->string('storage_path')->nullable(); //Путь к файлу с данными персонажа, если он хранится в файле
            $table->json('metadata')->nullable();
            $table->json('ability_scores')->nullable();
            $table->json('items')->nullable();
            $table->json('currency')->nullable();
            $table->json('resources')->nullable();
            $table->softDeletes();
            $table->unique(['user_id', 'slug']);

            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
