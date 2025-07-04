<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-7xl w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200"
                    x-data="orderForm({{ json_encode($products) }})">
                    <h1 class="text-2xl font-bold mb-6">Place New Order</h1>

                    {{-- Display Validation Errors --}}

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline">There were some problems with your input.</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Products</h3>
                        <p class="text-sm text-gray-600 mb-4">Add products to your order by selecting them from the dropdown below.</p>

                        <!-- Dynamic Product Rows -->
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center justify-between border p-4 rounded-md space-x-4">
                                    <!-- Product Select (left) -->
                                    <div class="flex-grow">
                                        <select :name="`products[${index}][id]`" x-model="item.id" @change="updatePrice(index)" class="w-full rounded-md shadow-sm border-gray-300">
                                            <option value="">-- Select a Product --</option>
                                            <template x-for="product in availableProducts(index)" :key="product.id">
                                                <option :value="product.id" x-text="`${product.name} (${formatCurrency(product.price)})`"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <!-- Quantity Input -->
                                    <div></div>
                                        <input
                                            type="number"
                                            :name="`products[${index}][quantity]`"
                                            x-model.number="item.quantity"
                                            min="1"
                                            class="w-24 rounded-md border-gray-300"
                                            placeholder="Qty"
                                        />
                                    </div>
                                    <!-- Row Total -->
                                    <div class="w-32 text-right">
                                        <span x-text="calculateRowTotal(index)"></span>
                                    </div>
                                    <!-- Remove Button -->
                                    <div>
                                        <button type="button" @click="removeItem(index)" class="text-blue-500 hover:text-red-700">
                                            Remove
                                        </button>
                                    </div>
                                    <!-- Jonnie Walker Image (far right) -->
                                    <div class="w-24 flex justify-center">
                                        <img
                                            src="/images/jonniewalker.jpg"
                                            alt="Jonnie Walker"
                                            class="h-16 w-16 object-cover rounded border"
                                        >
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Add Item Button -->
                        <div class="mt-4">
                            <button type="button" @click="addItem" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                + Add Another Product
                            </button>
                        </div>

                        <!-- Order Total -->
                        <div class="mt-6 pt-4 border-t">
                            <div class="flex justify-end items-center">
                                <span class="text-lg font-bold text-gray-700 mr-4">Grand Total:</span>
                                <span class="text-xl font-bold text-gray-900" x
                            </div>
                        </div>

                        <!-- Payment Option -->
                        <div class="mt-6">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select id="payment_method" name="payment_method" class="w-full rounded-md border-gray-300">
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="card">Card</option>
                            </select>
                        </div>

                        <!-- Payment Button -->
                        <div class="flex items-center justify-end mt-4">
                            <a
                                :href="`{{ route('payment.payment') }}?amount=${calculateGrandTotalRaw()}`"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                            >
                                Proceed to Payment
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button x-bind:disabled="items.length === 0 || items.some(i => !i.id || i.quantity < 1)">
                                {{ __('Place Order') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function orderForm(products) {
            return {
                products: products,
                items: [{ id: '', quantity: 1, price: 0 }],

                addItem() {
                    this.items.push({ id: '', quantity: 1, price: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                updatePrice(index) {
                    const selectedProduct = this.products.find(p => p.id == this.items[index].id);
                    this.items[index].price = selectedProduct ? selectedProduct.price : 0;
                },
                availableProducts(currentIndex) {
                    const selectedIds = this.items.map((item, index) => index !== currentIndex ? item.id : null).filter(id => id);
                    return this.products.filter(p => !selectedIds.includes(p.id));
                },
                calculateRowTotal(index) {
                    return this.items[index].quantity * this.items[index].price;
                },
                calculateGrandTotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                },
                formatCurrency(amount) {
                    return new Intl.NumberFormat('en-UG', { style: 'currency', currency: 'UGX', minimumFractionDigits: 0 }).format(amount);
                }
            }
        }
    </script>
</x-app-layout>
