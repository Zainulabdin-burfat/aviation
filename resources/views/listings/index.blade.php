@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Listings</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('listings.create') }}" class="btn btn-primary mb-3">Create New Listing</a>

        <form action="{{ route('listings.index') }}" method="GET">
            <div class="row">
                <div class="mb-3 col-sm-2">
                    <label for="aircraft_model" class="form-label">Aircraft Model</label>
                    <input type="text" class="form-control" id="aircraft_model" name="aircraft_model" value="{{ request('aircraft_model') }}">
                </div>

                <div class="mb-3 col-sm-2">
                    <label for="year" class="form-label">Year</label>
                    <input type="text" class="form-control" id="year" name="year" value="{{ request('year') }}">
                </div>

                <div class="mb-3 col-sm-2">
                    <label for="condition" class="form-label">Condition</label>
                    <input type="text" class="form-control" id="condition" name="condition" value="{{ request('condition') }}">
                </div>

            </div>
            <div class="row">

                <div class="mb-3 col-sm-2">

                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="reset" class="btn btn-secondary">Clear</button>
                </div>
            </div>

        </form>


        @if($listings->isEmpty())
        <p>No listings found.</p>
        @else
        <table class="table">
            <!-- Table headers -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aircraft Model</th>
                    <th>Year</th>
                    <th>Condition</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Seller</th>
                    <th>Chat</th>
                    <th>Buy</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through your search results and display them -->
                @foreach ($listings as $listing)
                <tr>
                    <td>{{ $listing->id }}</td>
                    <td>{{ $listing->aircraft_model }}</td>
                    <td>{{ $listing->year }}</td>
                    <td>{{ $listing->condition }}</td>
                    <td>{{ $listing->price }}</td>
                    <td>{{ $listing->description }}</td>
                    <td>
                        @if ($listing->images)
                        <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}" alt="{{ $listing->aircraft_model }}" width="50">
                        @else
                        No Image
                        @endif
                    </td>
                    <td>{{ $listing->user->first_name }}</td>
                    <td>
                        <a href="{{ route('messages.chat', ['user' => $listing->user->id]) }}" class="btn btn-primary btn-sm">Chat</a>
                    </td>
                    <td>
                        <form action="{{ route('listings.purchase', $listing->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Purchase</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('listings.show', $listing->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('listings.edit', $listing->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Add delete button and form for deletion -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection