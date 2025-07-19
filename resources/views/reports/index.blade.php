<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight: bold; font-size: 1.25rem; color: #2d3748;">
            📊 Reports Dashboard
        </h2>
    </x-slot>

    @php
        $roles = auth()->user()->getRoleNames()->toArray();
    @endphp

    {{-- Workforce Reports --}}
    <div class="box mb-4">
        <h3 style="font-size: 1.1rem; font-weight: bold;">🧑‍💼 Workforce Reports</h3>

        @if (array_intersect($roles, ['Finance', 'Liquor Manager', 'Procurement Officer']))
            <a href="{{ route('reports.task_performance') }}" class="button" target="_blank">
                ✅ Task Performance Report
            </a>

            <a href="{{ route('reports.shift_schedules') }}" class="button" style="margin-left: 10px;" target="_blank">
                🕒 Shift Schedule Report
            </a>
        @else
            <p style="color: #666; font-style: italic;">No access to workforce reports.</p>
        @endif
    </div>

    {{-- Inventory Reports --}}
    <div class="box mb-4">
        <h3 style="font-size: 1.1rem; font-weight: bold;">📦 Inventory Reports</h3>

        @if (array_intersect($roles, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
            <a href="{{ route('reports.stock_movements') }}" class="button" target="_blank">
                🚚 Stock Movement Report
            </a>

            <a href="{{ route('reports.inventory_chart') }}" class="button" style="margin-left: 10px;" target="_blank">
                📊 Inventory Category Chart
            </a>

            <a href="{{ route('reports.inventory.pdf') }}" class="button" style="margin-left: 10px;" target="_blank">
                📄 Download Weekly Inventory Report
            </a>
        @else
            <p style="color: #666; font-style: italic;">No access to inventory reports.</p>
        @endif
    </div>

    {{-- Sales Reports --}}
    <div class="box mb-4">
        <h3 style="font-size: 1.1rem; font-weight: bold;">💰 Sales Reports</h3>

        @if (array_intersect($roles, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
            <a href="{{ route('reports.sales.weekly') }}"
               class="button" target="_blank">
                📈 View Weekly Sales Report
            </a>

            <a href="{{ route('reports.sales.weekly.pdf') }}"
               class="button" style="margin-left: 10px;" target="_blank">
                📥 Download Sales Report (PDF)
            </a>
        @else
            <p style="color: #666; font-style: italic;">No access to sales reports.</p>
        @endif
    </div>
</x-app-layout>