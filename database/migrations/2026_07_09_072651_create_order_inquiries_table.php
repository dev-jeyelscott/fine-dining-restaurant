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
        Schema::create('order_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 120);
            $table->string('phone', 40);
            $table->string('email', 160);
            $table->string('fulfillment_type', 20)->index(); // pickup or delivery preference only
            $table->string('preferred_time', 80);
            $table->text('order_details')->nullable();
            $table->unsignedSmallInteger('quantity');
            $table->text('special_instructions')->nullable();
            $table->text('delivery_address')->nullable();
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
        Schema::dropIfExists('order_inquiries');
    }
};
