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
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('product_tags', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('product_tags', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
};
