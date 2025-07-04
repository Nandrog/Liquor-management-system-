<x-app-layout>
    <h1 class="h2 mb-4">Edit Item: {{ $item->name }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('manager.items.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Specify the HTTP method for updating --}}
                @include('manager.items.partials.form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Item</button>
                    <a href="{{ route('manager.items.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>