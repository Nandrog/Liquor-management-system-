{{-- This form is used for both creating and editing items --}}
{{-- It expects an optional $item variable for editing --}}
<div class="row">
<div class="col-md-6 mb-3">
<label for="name" class="form-label">Item Name</label>
<input type="text" class="form-control" id="name" name="name" value="{{ old('name', $item->name ?? '') }}" required>
</div>
<div class="col-md-6 mb-3">
<label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
<input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $item->sku ?? '') }}" required>
</div>
</div>
<div class="mb-3">
<label for="description" class="form-label">Description</label>
<textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
</div>
<div class="row">
<div class="col-md-6 mb-3">
<label for="type" class="form-label">Item Type</label>
<select class="form-select" id="type" name="type" required>
<option value="finished_good" @selected(old('type', $item->type ?? '') == 'finished_good')>Finished Good</option>
<option value="raw_material" @selected(old('type', $item->type ?? '') == 'raw_material')>Raw Material</option>
</select>
</div>
<div class="col-md-6 mb-3">
<label for="category_id" class="form-label">Category</label>
<select class="form-select" id="category_id" name="category_id" required>
@foreach($categories as $category)
<option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>{{ $category->name }}</option>
@endforeach
</select>
</div>
</div>
<div class="row">
<div class="col-md-6 mb-3">
<label for="unit_price" class="form-label">Unit Price (Sh.)</label>
<input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price', $item->unit_price ?? '0.00') }}" required>
</div>
<div class="col-md-6 mb-3">
<label for="unit_of_measure" class="form-label">Unit of Measure</label>
<input type="text" class="form-control" id="unit_of_measure" name="unit_of_measure" placeholder="e.g., bottle, kg, liter" value="{{ old('unit_of_measure', $item->unit_of_measure ?? '') }}" required>
</div>
</div>
{{-- Note: We can add JavaScript here later to show/hide these based on Item Type selection --}}
<div class="row">
<div class="col-md-6 mb-3">
<label for="supplier_id" class="form-label">Supplier (for Raw Materials)</label>
<select class="form-select" id="supplier_id" name="supplier_id">
<option value="">None</option>
@foreach($suppliers as $supplier)
<option value="{{ $supplier->id }}" @selected(old('supplier_id', $item->supplier_id ?? '') == $supplier->id)>{{ $supplier->username }}</option>
@endforeach
</select>
</div>
<div class="col-md-6 mb-3">
<label for="vendor_id" class="form-label">Vendor (for Finished Goods)</label>
<select class="form-select" id="vendor_id" name="vendor_id">
<option value="">None</option>
@foreach($vendors as $vendor)
<option value="{{ $vendor->id }}" @selected(old('vendor_id', $item->vendor_id ?? '') == $vendor->id)>{{ $vendor->shop_name }}</option>
@endforeach
</select>
</div>
</div>