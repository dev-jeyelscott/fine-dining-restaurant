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
        Schema::create('order_inquiry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_inquiry_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('menu_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('item_name_snapshot', 180);
            $table->decimal('display_price_snapshot', 10, 2)->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_inquiry_id', 'menu_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_inquiry_items');
    }
};
