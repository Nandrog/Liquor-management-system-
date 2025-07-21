<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Sales Summary</title>
    <style>

        /* ... inside the <style> tag ... */
.report-summary {
    margin: 40px 15px 20px 15px;
    padding: 15px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    font-size: 13px;
    line-height: 1.6;
}
.report-summary h4 {
    margin-top: 0;
    font-size: 16px;
    color: #002060;
    border-bottom: 2px solid #4472c4;
    padding-bottom: 5px;
    margin-bottom: 10px;
}
.report-summary ul {
    padding-left: 20px;
}
        /* This CSS is embedded directly in the file to ensure the PDF looks identical to the web view. */
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: right; }
        th { background-color: #002060; color: white; font-weight: bold; text-align: center; }
        .header-row th { background-color: #4472c4; }
        .section-header { font-weight: bold; background-color: #d9e1f2; text-align: left; }
        .category-row { font-weight: bold; background-color: #e2efda; text-align: left; }
        .product-row { text-align: left; padding-left: 20px; }
        .total-row { font-weight: bold; background-color: #f2f2f2; border-top: 2px solid #333; }
        .text-left { text-align: left; }
        .download-btn { 
            display: block; 
            margin: 15px; 
            padding: 10px 15px; 
            background-color: #006400; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            text-align: center; 
            width: 150px; 
        }
    </style>
</head>
<body>

    {{-- This is a clever trick: The download button ONLY shows if this is the web view. --}}
    {{-- The controller sets `$is_pdf` to true only when generating the PDF. --}}
    @if(!$is_pdf)
        <a href="{{ route('reports.weekly_summary.download') }}" class="download-btn">Download as PDF</a>
       
   
    @endif

    <table>
        <thead>
            {{-- Main Header Row with app Name and Dates --}}
            <tr class="header-row">
                <th style="width: 18%;">Store Name</th>
                <th colspan="{{ count($days) }}">{{ $appName }}</th>
                <th>Week Start - Week End</th>
                <th colspan="2">{{ $weekStartDate }} - {{ $weekEndDate }}</th>
            </tr>
            {{-- Column Headers --}}
            <tr>
                <th class="text-left">SALES</th>
                @foreach($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
                <th>Total</th>
                <th>Percent</th>
            </tr>
        </thead>
        <tbody>
            <!-- This is the main loop that builds the report body -->
            @foreach($report as $categoryData)
                {{-- 1. Print the Category Header Row --}}
                <tr class="category-row">
                    <td>{{ $categoryData['category'] }}</td>
                    <td colspan="{{ count($days) + 2 }}"></td> {{-- Span across all other columns --}}
                </tr>

                {{-- 2. Loop through each product within that category --}}
                @foreach($categoryData['products'] as $productData)
                    <tr>
                        <td class="product-row">{{ $productData['name'] }}</td>
                        {{-- Loop through the days of the week to print daily sales --}}
                        @foreach($days as $day)
                            <td>{{ number_format($productData['daily_sales'][$day] ?? 0, 0) }}</td>
                        @endforeach
                        <td>{{ number_format($productData['total'] ?? 0, 0) }}</td>
                        <td></td> {{-- Percent is only shown at the category level --}}
                    </tr>
                @endforeach
                
                {{-- 3. Print the Total Row for the Category --}}
                 <tr class="total-row" style="background-color: #d9e1f2;">
                    <td class="text-left">Total {{ $categoryData['category'] }}</td>
                    @php
                        // This block calculates the total for each day within the current category
                        $dayTotalsForCategory = array_fill_keys($days, 0);
                        foreach($categoryData['products'] as $pData) {
                            foreach($days as $day) {
                                $dayTotalsForCategory[$day] += $pData['daily_sales'][$day] ?? 0;
                            }
                        }
                    @endphp
                    @foreach($days as $day)
                        <td>{{ number_format($dayTotalsForCategory[$day], 0) }}</td>
                    @endforeach
                    <td>{{ number_format($categoryData['category_total'], 0) }}</td>
                    <td>{{ number_format($categoryData['percent'], 1) }}%</td>
                </tr>
            @endforeach

            <!-- Grand Total Row -->
            <tr class="total-row" style="font-size: 14px; border-top: 3px solid black;">
                <td class="text-left">Total Sales</td>
                @php 
                    // This block calculates the grand total for each day across ALL categories
                    $grandDayTotals = array_fill_keys($days, 0);
                    foreach($report as $catData) {
                        foreach($catData['products'] as $prodData) {
                            foreach($days as $day) {
                                $grandDayTotals[$day] += $prodData['daily_sales'][$day] ?? 0;
                            }
                        }
                    }
                @endphp
                @foreach($days as $day)
                    <td>{{ number_format($grandDayTotals[$day], 0) }}</td>
                @endforeach
                <td>{{ number_format($totalSales, 0) }}</td>
                <td>100.0%</td>
            </tr>
        {{-- This is the end of your report table --}}
        </tbody>
    </table>
    @if(!$is_pdf)
    <div style="width: 50%; margin: 20px auto; height: 400px;">
        <canvas id="categoryPieChart"></canvas>
    </div>
@endif
@if(!$is_pdf && $totalSales > 0)
    <div class="report-summary">
        <h4>Report Summary & Insights</h4>
        
        <ul>
            <li>
                <strong>Top Sales Day:</strong> The busiest day this week was <strong>{{ $topDay }}</strong>, indicating the highest customer traffic or largest purchases occurred on this day.
            </li>
            <li>
                <strong>Top Performers:</strong> The best-selling product was <strong>{{ $topProduct['name'] }}</strong>, making it a key driver of revenue. The top category overall was <strong>{{ $topCategory['category'] }}</strong>.
            </li>
        </ul>

        <h4>Analysis of Low-Selling Products</h4>
        <p>
            The following products had the lowest sales this week:
            <ul>
                @forelse($bottomProducts as $product)
                    <li><strong>{{ $product['name'] }}</strong> (Total Sales: UGX {{ number_format($product['total'], 0) }})</li>
                @empty
                    <li>No products with low sales data to analyze.</li>
                @endforelse
            </ul>
            Low performance for these items could be due to several factors, including low customer demand, competitive pricing from other stores, low visibility in the store, or potential stock shortages.
        </p>

        <h4>Recommendations & Way Forward</h4>
        <ul>
            <li>
                <strong>Capitalize on Peak Days:</strong> Consider running promotions or ensuring popular items are well-stocked leading up to and on <strong>{{ $topDay }}</strong> to maximize sales.
            </li>
            <li>
                <strong>Leverage Top Products:</strong> Ensure <strong>{{ $topProduct['name'] }}</strong> and other items in the <strong>{{ $topCategory['category'] }}</strong> category are prominently displayed. Consider bundling them with other products.
            </li>
            <li>
                <strong>Address Underperformers:</strong> For the low-selling products, consider a marketing push, a pricing review to ensure competitiveness, or gathering customer feedback. If sales do not improve, it may be strategic to reduce stock or delist these items to optimize inventory.
            </li>
        </ul>
    </div>
@endif
    
    {{-- The @if block now correctly wraps both script tags --}}
    @if(!$is_pdf)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Get the data from the controller.
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);
        
            // Get the canvas element from the HTML
            const ctx = document.getElementById('categoryPieChart');
        
            // Create the new pie chart
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Sales by Category',
                        data: chartData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Weekly Sales Distribution by Category',
                            font: {
                                size: 18
                            }
                        },
                          tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                // Add the percentage sign to the tooltip
                                label += context.parsed.toFixed(1) + '%';
                            }
                            return label;
                        }
                    }
                }
                    }
                }
            });
        </script>
    @endif

</body> {{-- There should only be ONE closing body tag --}}
</html> {{-- And ONE closing html tag --}}
