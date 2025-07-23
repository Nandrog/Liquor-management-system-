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
    <div class="report-card">
        <h3 style="font-size: 1.1rem; font-weight: bold;">🧑‍💼 Workforce Reports</h3>

        @if (array_intersect($roles, ['Finance', 'Liquor Manager', 'Procurement Officer','Manufacturer']))
         <div class="report-card-actions">
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
    </div>





                 @hasanyrole('Admin|Finance|Liquor Manager|Procurement Officer|Manufacturer|Supplier')
    <div class="report-card">
        <h3>Inventory Reports</h3>
        <p>Status and valuation of current inventory for finished goods and raw materials.</p>
        
        {{-- This div uses the 'report-card-actions' class to align buttons horizontally --}}
        <div class="report-card-actions">
            
            {{-- Link for Finance & Management --}}
            @hasanyrole('Admin|Finance|Liquor Manager')
                <a href="{{ route('reports.inventory.finance') }}" class="btn btn-primary">
                    Finished Goods Valuation
                </a>
            @endhasrole

            {{-- Link for Procurement & Management --}}
            @hasanyrole('Admin|Procurement Officer|Liquor Manager')
                 <a href="{{ route('reports.inventory.procurement') }}" class="btn btn-primary">
                    Finished Goods Procurement
                </a>
            @endhasanyrole

            {{-- Link for Raw Materials Procurement --}}
            @hasanyrole('Admin|Procurement Officer|Manufacturer|Liquor Manager')
                <a href="{{ route('reports.inventory.raw_materials') }}" class="btn btn-primary" >
                    Raw Material Stock
                </a>
            @endhasrole

            {{-- Link for the Supplier's unique view --}}
            @hasrole('Supplier')
                <a href="{{ route('supplier.dashboard') }}" class="btn btn-primary">
                    Supplier Dashboard
                </a>
            @endhasrole
            @hasanyrole('Admin|Procurement Officer|Liquor Manager|Manufacturer|Finance')
                                     <a href="{{ route('reports.stock_movements') }}" class="btn btn-primary">
                                        View Stock Movement
                                    </a>
                                @endhasanyrole
                                @hasanyrole('Admin|Procurement Officer|Liquor Manager|Finance')
                                     <a href="{{ route('reports.inventory_chart') }}" class="btn btn-primary">
                                        View inventory graph
                                    </a>
                                @endhasanyrole
            
        </div>
    </div>
@endhasanyrole


    {{-- Sales Report --}}
   

{{-- This @hasrole directive is a SECURITY feature. 
     It ensures that only users with these roles will see this card.
      --}}


{{-- The @hasrole security check is still here and is very important --}}
@hasrole('Liquor Manager|Finance')

<div class="report-card">
    
    <h3>Sales Reports</h3>
    {{-- Sales Reports --}}
    <div class="box mb-4">
        <h3 style="font-size: 1.1rem; font-weight: bold;">💰 Sales Reports</h3>

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