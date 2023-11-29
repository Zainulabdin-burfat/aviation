@extends('layouts/layoutMaster')

@section('title', 'User Management')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
<script>
    const getPermissions = @json(auth()->user()->roles);
    const getDirectPermissions = @json(auth()->user()->permissions);
    const userRole = getDirectPermissions.length ? 'super-admin' : getPermissions[0].name;
    const userPermissions = getDirectPermissions.length ? [] : getPermissions[0].permissions;

    function hasPermission(permissionName) {
        if (userRole === 'super-admin') {
            return true;
        }

        return userPermissions.some(permission => permission.name === permissionName);
    }


</script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/users.js') }}"></script>
    <script>
        $(function() {
            @if (session('message'))
                @if (session('status'))
                    toastr.success("{{ session('message') }}");
                @else
                    toastr.error("{{ session('message') }}");
                @endif
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
@endsection

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
