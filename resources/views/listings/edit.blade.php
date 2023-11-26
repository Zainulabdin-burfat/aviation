@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Listing</h5>
                </div>
                <div class="card-body">
                    <form class="edit-listing pt-0 needs-validation" id="editListingForm"
                          action="{{ route('listing.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="aircraft_model" class="form-label">Aircraft Model</label>
                                <input type="text" class="form-control" id="aircraft_model" placeholder="Aircraft Model"
                                       name="aircraft_model" aria-label="Aircraft Model"
                                       value="{{ old('aircraft_model', $listing->aircraft_model) }}">
                                @error('aircraft_model')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-6">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" placeholder="Year"
                                       name="year" aria-label="Year" value="{{ old('year', $listing->year) }}">
                                @error('year')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Add other fields similar to the create form -->

                            <div class="mb-3 col-sm-6">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('listing.index') }}" class="btn btn-secondary me-3">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
