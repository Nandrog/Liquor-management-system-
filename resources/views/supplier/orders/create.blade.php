<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a New Supplier Order
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('supplier.orders.store') }}" method="POST">
                        @csrf
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Select Raw Materials to Supply</h3>
                        <div class="mt-6 space-y-6">

                            @foreach($rawMaterials as $product)
                            <div class="product-item border-t border-gray-200 pt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="product-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <span class="ml-3 text-base font-medium text-gray-900">{{ $product->name }}</span>
                                </label>

                                <div class="product-details hidden ml-7 mt-4 space-y-4">
                                    <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}" disabled>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <input class="form-control mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 shadow-sm" type="number" name="products[{{ $loop->index }}][quantity]" placeholder="Quantity" min="1" disabled>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price per Unit (Set by Manager)</label>

                                        {{-- This input correctly places the fixed price inside the box --}}
                                        <input class="form-control mt-1 block w-full sm:w-1/2 bg-gray-100 rounded-md border-gray-300 shadow-sm"
                                                type="number"
                                                name="products[{{ $loop->index }}][price]"
                                                value="{{ $product->unit_price }}"
                                                readonly
                                                disabled>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <div class="mt-8 pt-5">
                            <div class="flex justify-end">
                                <button class="auth-button-yellow auth-button" type="submit">Submit Offer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Your JavaScript is correct and does not need changes.
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const itemContainer = this.closest('.product-item');
                    const details = itemContainer.querySelector('.product-details');
                    const inputs = details.querySelectorAll('input');
                    if (this.checked) {
                        details.classList.remove('hidden');
                        inputs.forEach(input => input.disabled = false);
                    } else {
                        details.classList.add('hidden');
                        inputs.forEach(input => input.disabled = true);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
