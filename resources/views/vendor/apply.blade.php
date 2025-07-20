<!-- resources/views/vendor/apply.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Vendor Application Form</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="vendor_name" class="form-label">Vendor Name</label>
            <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="application_pdf" class="form-label">Upload Application PDF</label>
            <input type="file" name="application_pdf" class="form-control" accept=".pdf" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>
@endsection
