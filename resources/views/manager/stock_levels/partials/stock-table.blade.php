@props(['stockLevels'])
<div class="table-responsive">
<table class="table table-sm table-hover align-middle">
<thead>
<tr>
<th>Product Name</th>
<th>SKU</th>
<th class="text-end">Quantity On Hand</th>
<th class="text-center">Status</th>
</tr>
</thead>
<tbody>
@foreach ($stockLevels->sortBy('product.name') as $stockLevel)
<tr>
<td>{{ $stockLevel->product->name }}</td>
<td><span class="font-monospace">{{ $stockLevel->product->sku }}</span></td>
<td class="text-end fw-bold">{{ $stockLevel->quantity }} {{ $stockLevel->product->unit_of_measure }}s</td>
<td class="text-center">
@if($stockLevel->quantity <= $stockLevel->product->reorder_level)
<span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Low Stock</span>
@else
<span class="badge bg-success-subtle text-success-emphasis rounded-pill">In Stock</span>
@endif
</td>
</tr>
@endforeach
</tbody>
</table>
</div>