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

    {{-- Sales Report --}}
   

{{-- This @hasrole directive is a SECURITY feature. 
     It ensures that only users with these roles will see this card.
     Make sure the roles match the ones in your controller. --}}


{{-- The @hasrole security check is still here and is very important --}}
@hasrole('Liquor Manager|Finance|Supplier')

<div class="report-card">
    
    <h3>Sales Reports</h3>

    <p>View or download the weekly sales summary report.</p>

    <div class="report-card-actions">
        
        {{-- Link to VIEW the report, styled as a primary button --}}
        <a href="{{ route('reports.weekly_summary.show') }}" class="btn btn-primary">
            View Report
        </a>

        {{-- Link to DOWNLOAD the report, styled as a success button --}}
        <a href="{{ route('reports.weekly_summary.download') }}" class="btn btn-success">
            Download PDF
        </a>

    </div>

</div>

@endhasrole

   

</x-app-layout>