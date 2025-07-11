<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Supplier Order #{{ $order->id }}
        </h2>
    </x-slot>

    <p><strong>Supplier:</strong> {{ $order->supplier->name }}</p>
    <p><strong>Status:</strong> {{ $order->status->value }}</p>
    <p><strong>Total:</strong> UGX{{ number_format($order->total_amount, 2) }}</p>

    <h3>Items:</h3>
    <ul>
        @foreach($order->items as $item)
        <li>{{ $item->product->name }} - Qty: {{ $item->quantity }} @ UGX{{ number_format($item->unitprice, 2) }} each</li>
        @endforeach
    </ul>

    @if($order->status == \App\Enums\OrderStatus::PENDING_APPROVAL)
    <div class="mt-4 flex space-x-4">
        <form action="{{ route('manufacturer.orders.update', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="confirmed">
            <button class="auth-button-green auth-button" type="submit" class="bg-blue-500 text-red p-2 rounded">Accept & Pay</button>
        </form>
        <br>
        <form action="{{ route('manufacturer.orders.update', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button class="auth-button-yellow auth-button" type="submit" class="bg-red-500 text-white p-2 rounded">Reject</button>
        </form>
    </div>
    @endif
</x-app-layout>
