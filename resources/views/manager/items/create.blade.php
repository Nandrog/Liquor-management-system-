<x-app-layout>
    <h1 class="h2 mb-4">Create New Item</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- We will use a shared form partial for both create and edit --}}
            <form action="{{ route('manager.items.store') }}" method="POST">
                @csrf
                @include('manager.items.partials.form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Item</button>
                    <a href="{{ route('manager.items.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>