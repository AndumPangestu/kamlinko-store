@props(['transaction'])
<div class="">
    <div class="flex flex-row justify-between gap-5">
        <section class="px-5 w-[70%] flex flex-col divide-y gap-3">
            @if ($transaction->userAddress)
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Address</span>
                    <p class="text-end">{{ $transaction->userAddress->address }}</p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Province</span>
                    <p class="text-end">{{ $transaction->userAddress->province }}</p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>City</span>
                    <p class="text-end">{{ $transaction->userAddress->city }}</p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Subdistrict</span>
                    <p class="text-end">{{ $transaction->userAddress->subdistrict }}</p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>ZIP</span>
                    <p class="text-end">{{ $transaction->userAddress->zip }}</p>
                </div>
            @else
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Address</span>
                    <p class="text-end">
                        <span class="text-red-500">No Address</span>
                    </p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Province</span>
                    <p class="text-end">
                        <span class="text-red-500">No Address</span>
                    </p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>City</span>
                    <p class="text-end">
                        <span class="text-red-500">No Address</span>
                    </p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>Subdistrict</span>
                    <p class="text-end">
                        <span class="text-red-500">No Address</span>
                    </p>
                </div>
                <div class="flex flex-row gap-5 pt-3 justify-between">
                    <span>ZIP</span>
                    <p class="text-end">
                        <span class="text-red-500">No Address</span>
                    </p>
                </div>
            @endif
            <div class="flex flex-row gap-5 pt-3 justify-between">
                <span>Delivery Method</span>
                <p class="text-end">{{ $transaction->delivery_method }}</p>
            </div>
            <div class="flex flex-row gap-5 pt-3 justify-between">
                <span>Delivery Service</span>
                <p class="text-end">{{ $transaction->delivery_service }}</p>
            </div>
            @if (in_array($transaction->transaction_status, ['On Process', 'On Delivery', 'Delivered', 'Completed']))
                <div>
                    @if (in_array($transaction->transaction_status, ['On Delivery', 'Delivered', 'Completed']))
                        <div class="flex flex-row gap-5 pb-5 pt-3 justify-between">
                            <span>Delivery Date</span>
                            <p class="text-end">{{ $transaction->delivery_date }}</p>
                        </div>
                    @endif
                    <form class="flex flex-col gap-5" method="POST"
                        onsubmit="return confirm('Are you sure you want to confirm the delivery?');"
                        action="{{ route('admin.transaction-management.confirm-delivery', $transaction->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center justify-between">
                            <label for="tracking_number">Tracking Number/Delivery Number</label>
                            <input type="text" name="tracking_number" class="text-end"
                                placeholder="Input Tracking Number" value="{{ $transaction->tracking_number ?? '' }}"
                                {{ in_array($transaction->transaction_status, ['On Process', 'On Delivery']) ? '' : 'disabled' }} />
                        </div>
                        @if (in_array($transaction->transaction_status, ['On Process', 'On Delivery']))
                            <span class="flex justify-center">
                                <button class="text-white bg-sky-500 p-3 rounded-md w-full" type="submit">
                                    {{ $transaction->transaction_status == 'On Process' ? 'Confirm Delivery' : 'Update Delivery' }}
                                </button>
                            </span>
                        @endif
                    </form>
                </div>
            @endif

            <section class="pt-3">
                {{-- <span>Map</span> --}}
                @if ($transaction->userAddress)
                    <div class="flex flex-row gap-5 justify-center">
                        @include('components.map', [
                            'longitude' => $transaction->userAddress->longitude,
                            'latitude' => $transaction->userAddress->latitude,
                        ])
                    </div>
                @else
                    <div class="flex flex-row gap-5 justify-center">
                        <span class="text-red-500">No Address</span>
                    </div>
                @endif
            </section>
        </section>

        <section class="justify-between flex flex-col">
            <div class="flex flex-col gap-5">
                <div class="flex flex-row gap-5 justify-between text-xl">
                    <span>Status</span>
                    <p class="text-end">{{ $transaction->transaction_status }}</p>
                </div>
                <div class="flex flex-row gap-5 justify-between text-xl">
                    <span>Payment Method</span>
                    <p class="text-end">{{ $transaction->payment_method }}</p>
                </div>
            </div>
            <!-- Action button based on transaction status -->
            <div class="flex flex-col gap-3">
                <span class="text-sm text-gray-500">
                    <p>Transaction handled by:</p>
                    <a class="hover:text-blue-500"
                        href="{{ $transaction->action_taken_by ? route('admin.user-management.view-user-details', $transaction->action_taken_by) : '#' }}">{{ $transaction->action_taken_by ? $transaction->admin->name : 'Unknown/Deleted Admin' }}</a>
                </span>
                <div>
                    @if ($transaction->transaction_status == 'Payment Received')
                        <!-- Accept/Decline Payment -->
                        <div class="flex flex-col gap-2 pt-2">
                            <form method="POST"
                                onsubmit="return confirm('Are you sure you want to accept the payment?');"
                                action="{{ route('admin.transaction-management.accept-payment', $transaction->id) }}">
                                @csrf
                                @method('PUT')
                                <button class="text-white bg-green-500 p-3 rounded-md w-full">Accept
                                    Payment</button>
                            </form>

                            <form method="POST"
                                onsubmit="return confirm('Are you sure you want to decline the payment?');"
                                action="{{ route('admin.transaction-management.reject-payment', $transaction->id) }}">
                                @csrf
                                @method('PUT')
                                <button class="text-white bg-red-500 p-3 rounded-md w-full">Decline Payment</button>
                            </form>
                        </div>
                    @elseif (in_array($transaction->transaction_status, ['On Delivery']))
                        <!-- Delivered -->
                        <div class="flex flex-col gap-2 pt-2">
                            <form method="POST"
                                onsubmit="return confirm('Are you sure you want to mark the transaction as delivered?');"
                                action="{{ route('admin.transaction-management.deliver-transaction', $transaction->id) }}">
                                @csrf
                                @method('PUT')
                                <button class="text-white bg-sky-500 p-3 rounded-md w-full">Mark as Delivered</button>
                            </form>
                        </div>
                    @endif
                </div>
                @if (in_array($transaction->transaction_status, ['Waiting Payment', 'On Process', 'On Delivery']))
                    <!-- Cancel -->
                    <div>
                        <div class="flex flex-col gap-2 pt-2">
                            <form onsubmit="return confirm('Are you sure you want to cancel the transaction?');"
                                action="{{ route('admin.transaction-management.cancel-transaction', $transaction->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button class="text-white bg-red-500 p-3 rounded-md w-full">Cancel</button>
                            </form>
                        </div>
                    </div>
                @endif
                @if (in_array($transaction->transaction_status, ['Completed']))
                    <!-- Invoice -->
                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('admin.transaction-management.view-invoice', $transaction->id) }}"
                            class="text-white bg-sky-500 p-3 rounded-md w-full flex flex-row items-center justify-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                                class="bi bi-eye" viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                            </svg>
                            <p class="text-lg">View Invoice</p>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
