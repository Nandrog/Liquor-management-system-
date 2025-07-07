<?php

namespace App\Modules\Production\Http\Controllers\Manufacturer;

use App\Http\Controllers\Controller;
use App\Modules\Production\Services\ProductionService;
use Illuminate\Http\Request;
use App\Models\ProductionRun;
use App\Models\Product;
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
$producibleItems = Product::where('type', 'finished_good')
->whereHas('recipe')
->orderBy('name')
->get();

$productionRuns = ProductionRun::where('user_id', auth()->id())
->with(['user', 'product'])
->latest('completed_at')
->paginate(10);

return view('manufacturer.production.index', [
'producibleItems' => $producibleItems,
'productionRuns' => $productionRuns,
]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
'product_id' => 'required|integer|exists:products,id',
'crates' => 'required|integer|min:1'
]);

$result = $this->productionService->createProductionRun(
auth()->user(),
$request->input('product_id'),
$request->input('crates')
);

if (!$result['sufficient']) {
return back()->with('error', $result['message'])->with('errors', $result['errors']);
}

return back()->with('success', $result['message'] . " Cost of materials used: Sh. " . number_format($result['cost'], 2));
    }
}