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
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 120);
            $table->string('phone', 40);
            $table->string('email', 160);
            $table->date('preferred_date')->index();
            $table->string('preferred_time', 40);
            $table->unsignedSmallInteger('guest_count');
            $table->text('special_requests')->nullable();
            $table->boolean('is_banquet_or_event')->default(false)->index();
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
        Schema::dropIfExists('reservation_requests');
    }
};
