<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\FinancialRecord;
use App\Models\ProductionMetric;
use App\Models\SupplierDelivery;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function customerSegmentation()
    {
        $segments = $this->analyticsService->segmentCustomers();

        $segmentSummary = collect($segments)->groupBy('segment')->map(function ($group){
            return $group->count();
        });

        return view('analytics.customer_segmentation', [
            'segments' => $segments,
            'segmentSummary' => $segmentSummary,
        ]);
    }


}
