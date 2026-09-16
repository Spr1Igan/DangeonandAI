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
        Schema::create('race_versions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('race_id')->constrained();
            $table->unsignedInteger('version');
            $table->text('description')->nullable(); //Описание расы, её происхождение и особенности
            $table->text('rules_text')->nullable();  //Правила обычным текстом: ограничения, исключения, условия
            $table->json('metadata')->nullable();    //Дополнительные служебные сведения, если понадобятся
            $table->json('mechanics')->nullable();  //Способности, скорость, сопротивления, бонусы и прочие механики
            $table->enum('status', ['draft', 'final'])->default('draft');


            $table->unique(['race_id', 'version']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_versions');
    }
};
