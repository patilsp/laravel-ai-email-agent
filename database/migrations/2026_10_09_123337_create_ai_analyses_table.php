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
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_message_id')->constrained('email_messages')->cascadeOnDelete();
            $table->string('model_version', 100)->default('claude-3-5-sonnet');
            $table->string('intent');
            $table->enum('urgency_level', ['urgent', 'important', 'routine'])->default('routine');
            $table->decimal('urgency_score', 3, 1)->default(1.0);
            $table->string('sentiment', 100)->nullable();
            $table->timestamp('deadline_detected')->nullable();
            $table->json('suggested_labels');
            $table->text('summary');
            $table->boolean('requires_reply')->default(true);
            $table->unsignedInteger('tokens_used')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['urgency_level', 'urgency_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
