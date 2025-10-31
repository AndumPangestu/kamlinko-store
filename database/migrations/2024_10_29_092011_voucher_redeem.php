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
        Schema::create('voucher_redeems', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignUuid('voucher_id')->constrained('vouchers', 'id')->cascadeOnDelete();
            $table->foreignUuid('transaction_id')->nullable()->constrained('transactions', 'id')->nullOnDelete();
            $table->boolean('is_used')->default(false);
            $table->date('redeem_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_redeems');
    }
};
