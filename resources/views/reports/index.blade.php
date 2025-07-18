<!-- resources/views/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight: bold; font-size: 1.25rem; color: #2d3748;">
            Dashboard
        </h2>
    </x-slot>

    @php
        $roles = auth()->user()->getRoleNames()->toArray();
    @endphp

    {{-- Workforce Reports --}}
    <div class="box mb-4">
        <h3>Workforce Reports</h3>

        @if (array_intersect($roles, ['Finance', 'Liquor Manager', 'Procurement Officer']))
            <a href="{{ route('reports.task_performance') }}" class="button" target="_blank">
                ✅ View Task Performance Report
            </a>

            <a href="{{ route('reports.shift_schedules') }}" class="button" style="margin-left: 10px;" target="_blank">
                🕒 View Shift Schedule Report
            </a>
        @else
            <p style="color: #666; font-style: italic;">You do not have access to workforce reports.</p>
        @endif
    </div>

    {{-- Inventory Report --}}
    <div class="box mb-4">
        <h3>Inventory Reports</h3>

        @if (array_intersect($roles, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
            <a href="{{ route('reports.stock_movements') }}" class="button" target="_blank">
                🚚 View Stock Movement Report
            </a>
        @else
            <p style="color: #666; font-style: italic;">You do not have access to inventory reports.</p>
        @endif
    </div>

    {{-- Sales Report Example --}}
    <div class="box mb-4">
        <h3>Sales Reports</h3>

        @if (array_intersect($roles, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
            <a href="{{ route('reports.sales.weekly') }}"
               class="button" target="_blank">
                📈 View Weekly Sales Report
            </a>
            <a href="{{ route('reports.sales.weekly.pdf') }}"
               class="button" style="background-color: #007BFF; margin-left: 10px;" target="_blank">
                📥 Download Weekly Sales Report (PDF)
            </a>
        @else
            <p style="color: #666; font-style: italic;">You do not have access to sales reports.</p>
        @endif
    </div>

</x-app-layout>