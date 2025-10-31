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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('product_tags', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('brands', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->foreignUuid('created_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignUuid('action_taken_by')->nullable()->constrained('users', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
