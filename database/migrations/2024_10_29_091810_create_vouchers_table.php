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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->float('value_percentage')->default(0);
            $table->float('value_fixed')->default(0);
            $table->float('minimum_transaction_value')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('used')->default(0);    
            $table->date('start_date')->default(now());
            $table->date('end_date')->default(now()->addDay());
            $table->text('description');
            $table->text('terms')->nullable();
            $table->enum('category', ['offline', 'online']);
            $table->enum('type', ['ongkir', 'transaction_item']);
            $table->boolean('is_one_time_use')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
