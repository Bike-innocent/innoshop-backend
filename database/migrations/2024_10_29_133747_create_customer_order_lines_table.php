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
        // Schema::create('customer_order_lines', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('order_id')->constrained('customer_orders')->onDelete('cascade');
        //     $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        //     $table->integer('quantity');
        //     $table->decimal('price', 10, 2);
        //     $table->timestamps();
        //     $table->softDeletes();
        // });


        Schema::create('customer_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('customer_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_order_lines');
    }
};
