@props(['transaction'])
<div class="text-lg flex flex-col gap-5">
    <div class="flex flex-row gap-5 justify-between">
        <span>Customer Name</span>
        <p class="text-end">{{ $transaction->user->name }}</p>
    </div>
    {{-- <div class="flex flex-row gap-5 justify-between">
        <span>Customer Username</span>
        <p class="text-end">{{ $transaction->user->username }}</p>
    </div> --}}
    <div class="flex flex-row gap-5 justify-between">
        <span>Email</span>
        <p class="text-end">{{ $transaction->user->email }}</p>
    </div>
    <div class="flex flex-row gap-5 justify-between">
        <span>Phone Number</span>
        <p class="text-end">{{ $transaction->user->phone }}</p>
    </div>

</div>
