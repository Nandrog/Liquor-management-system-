<!-- resources/views/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 style="font-weight: bold; font-size: 1.25rem; color: #2d3748;">
            Dashboard
        </h2>
    </x-slot>

    {{-- Inventory Report Box --}}
    <div class="box">
        <h3>Inventory Reports</h3>

        @php
            $roles = auth()->user()->getRoleNames()->toArray();
        @endphp

        @if (array_intersect($roles, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
            <a href="{{ route('reports.inventory') }}" 
               class="button" target="_blank">
                📄 Download Inventory Report (PDF)
            </a>
        @else
            <p style="color: #666; font-style: italic;">You do not have access to inventory reports.</p>
        @endif
    </div>

    {{-- Sales Report Box --}}
    <div class="box">
        <h3>Sales Reports</h3>

        @php
            $role = auth()->user()->getRoleNames()->first();
        @endphp

        @if (in_array($role, ['Manufacturer', 'Supplier', 'Finance', 'Liquor Manager']))
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






