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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->primary();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('category_link')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('put_on_highlight');
            $table->timestamps();
        });

        Schema::create('product_tags', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->primary();
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('product_categories')->nullOnDelete();
            $table->unsignedInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('product_tags')->nullOnDelete();
            $table->foreignUuid('brand_id')->constrained('brands', 'id')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('long_description')->nullable(); // For RTE/WYSIWYG editor
            $table->boolean('put_on_highlight');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->string('name');
            $table->string('color');
            $table->float('price');
            $table->float('discount_fixed')->default(0);
            $table->float('discount_percentage')->default(0);
            $table->float('weight');
            $table->text('description');
            $table->string('sku');
            $table->integer('stock');
            $table->integer('total_sales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('product_images');
    }
};
