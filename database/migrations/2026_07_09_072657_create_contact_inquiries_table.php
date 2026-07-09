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
        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 120);
            $table->string('email', 160);
            $table->string('phone', 40)->nullable();
            $table->string('subject', 160)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
