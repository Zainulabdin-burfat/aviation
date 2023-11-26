@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create New Listing</h5>
                </div>
                <div class="card-body">
                    <form class="add-new-listing pt-0 needs-validation" id="addNewListingForm"
                        action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="aircraft_model" class="form-label">Aircraft Model</label>
                                <input type="text" class="form-control" id="aircraft_model" placeholder="Aircraft Model"
                                       name="aircraft_model" aria-label="Aircraft Model" value="{{ old('aircraft_model') }}">
                                @error('aircraft_model')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-6">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" placeholder="Year"
                                       name="year" aria-label="Year" value="{{ old('year') }}">
                                @error('year')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-6">
                                <label for="condition" class="form-label">Condition</label>
                                <input type="text" class="form-control" id="condition" placeholder="Condition"
                                       name="condition" aria-label="Condition" value="{{ old('condition') }}">
                                @error('condition')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" placeholder="Price"
                                       name="price" aria-label="Price" value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" placeholder="Description"
                                          name="description" aria-label="Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-sm-6">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary me-3">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
