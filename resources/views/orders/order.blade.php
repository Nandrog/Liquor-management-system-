@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Place New Order</h2>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">Customer</label>
            <select name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Products</label>
            @foreach($products as $product)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="products[{{ $product->id }}]" value="1">
                    <label class="form-check-label">
                        {{ $product->name }} (Stock: {{ $product->stock }})
                    </label>
                    <input type="number" name="quantities[{{ $product->id }}]" placeholder="Quantity" class="form-control mt-1">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Submit Order</button>
    </form>
</div>
@endsection
