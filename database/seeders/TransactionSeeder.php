<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionItems;
use App\Models\Transaction;
use App\Notifications\Admin\PaymentNeedApprovalNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class TransactionSeeder extends Seeder
{
    public function run()
    {
        Transaction::factory()
            ->count(5)
            ->create()
            ->each(function ($transaction) {
                $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->get();
                if ($transaction->transaction_status == 'Payment Received') {
                    Notification::send($admins, new PaymentNeedApprovalNotification($transaction));
                }

                // Generate Transaction Items
                $items = TransactionItems::factory()
                    ->count(rand(1, 3))
                    ->make(); // Use `make` to avoid saving immediately

                $subtotal = 0;
                $totalDiscount = 0;

                foreach ($items as $item) {
                    $item->transaction_id = $transaction->id;
                    $subtotal += $item->subtotal;
                    // $totalDiscount += ($item->quantity * $item->productType->price) - $item->subtotal;
                    $item->save();
                }

                // Calculate voucher effects
                $voucher = $transaction->voucher;
                $deliveryFee = $transaction->delivery_fee; // do not alter the original delivery fee
                $discountAmount = 0;
                if ($voucher) {
                    //Free Shipping
                    if ($voucher->type == 'ongkir') {
                        if ($voucher->value_percentage > 0) {
                            $discountAmount = $transaction->delivery_fee * ($voucher->value_percentage / 100);
                            if ($voucher->value_fixed > 0) {
                                $discountAmount = min($discountAmount, $voucher->value_fixed); // Apply the minimum discount
                            }
                            $deliveryFee = $transaction->delivery_fee - $discountAmount;
                        }
                        if ($voucher->value_fixed > 0) {
                            $deliveryFee = max(0, $transaction->delivery_fee - $voucher->value_fixed);
                            $discountAmount = max(0, $transaction->delivery_fee - $deliveryFee);
                        }
                    } elseif ($voucher->type == 'transaction_item') {
                        if ($voucher->value_percentage > 0) {
                            $discountAmount = $subtotal * ($voucher->value_percentage / 100);
                            if ($voucher->value_fixed > 0) {
                                $discountAmount = min($discountAmount, $voucher->value_fixed); // Apply the minimum discount
                            }
                        } elseif ($voucher->value_fixed > 0) {
                            $discountAmount = $voucher->value_fixed;
                        }
                    } // If Cashback, nothing happens
                }

                $totalDiscount += $discountAmount;
                // Calculate totals
                $tax = $subtotal * 0.1; // Example tax of 10%
                $total = $subtotal + $transaction->delivery_fee + $tax - $totalDiscount;

                // Update Transaction
                $transaction->subtotal = $subtotal;
                $transaction->total_discount = $totalDiscount;
                $transaction->tax = $tax;
                $transaction->total = $total;
                $transaction->save();

                // dummy transfer receipt
                $transaction->addMediaFromUrl('https://www.shutterstock.com/image-vector/register-sale-receipt-isolated-on-600nw-2197694315.jpg')->toMediaCollection();
                $transaction->addMediaFromUrl('https://img.freepik.com/premium-vector/paper-financial-check-icon-flat-style-payment-receipt-vector-illustration-isolated-background-document-paid-invoice-sign-business-concept_157943-11078.jpg')->toMediaCollection();
            });
    }
}
