<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sales Forecast</h2>
    </x-slot>

    <div class="p-6">
        <h4>Next 3 Months (Predicted Sales)</h4>
        <ul>
            @foreach ($data['predicted_sales'] as $sale)
                <li>${{ number_format($sale, 2) }}</li>
            @endforeach
        </ul>

        <p><strong>Efficiency:</strong> {{ $data['efficiency'] }}%</p>
        <p><strong>Avg Fulfillment Days:</strong> {{ $data['fulfillment_days'] }}</p>
    </div>
</x-app-layout>
