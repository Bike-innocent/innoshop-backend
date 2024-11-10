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
        // Schema::create('products', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('slug', 10)->unique();
        //     $table->foreignId('category_id')->constrained('product_categories');
        //     $table->foreignId('brand_id')->nullable()->constrained('brands');
        //     $table->foreignId('colour_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('size_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        //     $table->text('description');
        //     $table->decimal('price', 10, 2);
        //     $table->integer('stock_quantity');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 10)->unique();
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('colour_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            // Updated to reference the `users` table for suppliers
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity');
            $table->timestamps();
            $table->softDeletes();
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
