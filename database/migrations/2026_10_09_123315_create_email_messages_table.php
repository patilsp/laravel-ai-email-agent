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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gmail_message_id', 100)->unique();
            $table->string('gmail_thread_id', 100)->index();
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->index();
            $table->string('recipient_email');
            $table->string('subject', 500)->nullable();
            $table->text('snippet')->nullable();
            $table->longText('body_plain')->nullable();
            $table->longText('body_html')->nullable();
            $table->boolean('has_attachments')->default(false);
            $table->timestamp('received_at');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'received_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
