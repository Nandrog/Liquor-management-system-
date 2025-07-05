<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Item Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name ?? '') }}" required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="col-md-6 mb-3">
        <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $item->sku ?? '') }}" required>
        <x-input-error :messages="$errors->get('sku')" class="mt-2" />
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $item->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">Item Type</label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="finished_good" @selected(old('type', $item->type ?? '') == 'finished_good')>Finished Good</option>
            <option value="raw_material" @selected(old('type', $item->type ?? '') == 'raw_material')>Raw Material</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>
    <div class="col-md-6 mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="unit_price" class="form-label">Unit Price (Sh.)</label>
        <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" id="unit_price" name="unit_price" value="{{ old('unit_price', $item->unit_price ?? '0.00') }}" required>
        <x-input-error :messages="$errors->get('unit_price')" class="mt-2" />
    </div>
      <div class="col-md-6 mb-3">
        <label for="unit_of_measure" class="form-label">Unit of Measure</label>
        <select class="form-select @error('unit_of_measure') is-invalid @enderror" id="unit_of_measure" name="unit_of_measure" required>
            <option value="">Choose a unit...</option>
            {{-- Loop directly over the config array to create the options --}}
            @foreach (config('inventory.units_of_measure') as $unit)
                <option value="{{ $unit }}" @selected(old('unit_of_measure', $item->unit_of_measure ?? '') == $unit)>
                    {{-- Display the capitalized version to the user for better readability --}}
                    {{ ucfirst($unit) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('unit_of_measure')" class="mt-2" />
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="supplier_id" class="form-label">Supplier (for Raw Materials)</label>
        <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id">
            <option value="">None</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $item->supplier_id ?? '') == $supplier->id)>{{ $supplier->username }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
    </div>
    <div class="col-md-6 mb-3">
        <label for="vendor_id" class="form-label">Vendor (for Finished Goods)</label>
        <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id">
            <option value="">None</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}" @selected(old('vendor_id', $item->vendor_id ?? '') == $vendor->id)>{{ $vendor->shop_name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-2" />
    </div>
</div>

{{-- Reorder Level is missing from your original form, adding it here --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="reorder_level" class="form-label">Reorder Level</label>
        <input type="number" class="form-control @error('reorder_level') is-invalid @enderror" id="reorder_level" name="reorder_level" value="{{ old('reorder_level', $item->reorder_level ?? 0) }}" required>
        <x-input-error :messages="$errors->get('reorder_level')" class="mt-2" />
    </div>
</div>