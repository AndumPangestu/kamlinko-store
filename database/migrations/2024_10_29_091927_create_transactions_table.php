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
        /**
         * Create the transaction_status table that will store all options for transaction status such as
         * created, confirmed, paid, delivered, and completed.
         */

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique();
            $table->foreignUuid('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignUuid('user_address_id')->nullable()->constrained('user_addresses', 'id')->nullOnDelete();
            $table->foreignUuid('voucher_id')->nullable()->constrained('vouchers', 'id')->nullOnDelete();
            $table->enum('transaction_status', ['Initiating Order', 'Waiting Payment', 'Payment Received', 'Payment Rejected', 'On Process', 'On Delivery', 'Delivered', 'Completed', 'Cancelled']);
            $table->enum('payment_method', ['Bank Transfer', 'Credit Card'])->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('branch_store', ['sinar abadi home centre', 'sinar abadi sindang barang'])->nullable();
            $table->enum('delivery_method', ['JNE', 'TIKI', 'Shop Courier', 'Pick Up'])->nullable();
            $table->string('delivery_service')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->float('delivery_fee')->default(0);
            $table->string('tracking_number')->nullable();
            $table->float('total_discount')->default(0);
            $table->float('subtotal')->default(0);
            $table->float('tax')->default(0);
            $table->float('total')->default(0);
            $table->float('total_weight')->default(0);

            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->constrained('transactions', 'id')->cascadeOnDelete();
            $table->foreignUuid('product_type_id')->constrained('product_types', 'id')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->float('subtotal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transaction_status');
    }
};