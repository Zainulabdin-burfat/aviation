@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Listing Details</h5>
        </div>
        <div class="card-body">
            <h2>{{ $listing->aircraft_model }}</h2>
            <p>Year: {{ $listing->year }}</p>
            <p>Condition: {{ $listing->condition }}</p>
            <p>Price: {{ $listing->price }}</p>

            <!-- Display the image -->
            @if ($listing->images)
                <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" alt="{{ $listing->aircraft_model }}" class="img-fluid">
            @else
                No Image
            @endif

            <!-- Add more details as needed -->

            <div class="mt-3">
                <a href="{{ route('listings.index') }}" class="btn btn-secondary">Back to Listings</a>
            </div>
        </div>
    </div>
@endsection
