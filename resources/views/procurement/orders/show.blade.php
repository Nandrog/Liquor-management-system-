<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Vendor Order #{{ $order->id }}
        </h2>
    </x-slot>

    <p><strong>Vendor:</strong> {{ $order->vendor->name }}</p>
    <p><strong>Status:</strong> {{ $order->status->value }}</p>
    <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>

    @if($errors->any())
        <div class="text-red-500">{{ $errors->first() }}</div>
    @endif

    <h3>Items & Stock Check:</h3>
    <ul>
        @foreach($order->items as $item)
        <li>
            {{ $item->product->name }} - Qty Requested: {{ $item->quantity }}
            (Current Stock: {{ $item->product->stockLevels->first()->quantity ?? 0 }})
        </li>
        @endforeach
    </ul>

    @if($order->status == \App\Enums\OrderStatus::PENDING)
    <div class="mt-4 flex space-x-4">
        <form action="{{ route('procurement.orders.update', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="confirmed">
            <button type="submit" class="bg-green-500 text-white p-2 rounded auth-button-green auth-button">Confirm & Deduct Stock</button>
        </form>
        <br>
        <form action="{{ route('procurement.orders.update', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="bg-red-500 text-white p-2 rounded auth-button-yellow auth-button">Reject & Refund</button>
        </form>
    </div>
    @endif
</x-app-layout>
