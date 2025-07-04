<?php

namespace App\Modules\Production\Http\Controllers\Manufacturer;

use App\Http\Controllers\Controller;
use App\Modules\Production\Services\ProductionService;
use Illuminate\Http\Request;
use App\Models\ProductionRun;
use Illuminate\Support\Facades\Auth;


class ProductionController extends Controller
{
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

    public function index()
    {
        $manufacturer = Auth::user();

        $productionRuns = ProductionRun::where('user_id', $manufacturer->id)
            ->with(['user', 'product'])
            ->latest('completed_at') // 'latest' is a shortcut for orderBy('created_at', 'desc')
            ->paginate(10);

        return view('manufacturer.production.index', [
            'productionRuns' => $productionRuns,
        ]);
    }

    public function store(Request $request)
    {
        
        $request->validate(['crates' => 'required|integer|min:1']);

         $user = $request->user();
        
        $result = $this->productionService->createProductionRun(
            $user,
            'FG-UWG-750', // The SKU of the product to create
            $request->input('crates')
        );


        if (!$result['sufficient']) {
            return back()->with('error', $result['message'])->with('errors', $result['errors']);
        }

        return back()->with('success', $result['message'] . " Cost of materials used: Sh. " . number_format($result['cost'], 2));
    }
}