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
        Schema::create('email_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_message_id')->constrained('email_messages')->cascadeOnDelete();
            $table->foreignId('ai_analysis_id')->constrained('ai_analyses')->cascadeOnDelete();
            $table->longText('proposed_body');
            $table->longText('edited_body')->nullable();
            $table->enum('status', ['pending', 'approved', 'edited', 'rejected', 'sent'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->string('gmail_sent_message_id', 100)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_drafts');
    }
};
