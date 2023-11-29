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
                        <input type="text" class="form-control" id="aircraft_model" name="aircraft_model"
                               value="{{ request('aircraft_model') }}">
                    </div>

                    <div class="mb-3 col-sm-2">
                        <label for="year" class="form-label">Year</label>
                        <input type="text" class="form-control" id="year" name="year" value="{{ request('year') }}">
                    </div>

                    <div class="mb-3 col-sm-2">
                        <label for="condition" class="form-label">Condition</label>
                        <input type="text" class="form-control" id="condition" name="condition"
                               value="{{ request('condition') }}">
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
                        @if(auth()->user()->hasPermissionTo('listing.purchase') || auth()->user()->hasDirectPermissionTo('listing.purchase'))
                            <th>Buy</th>
                        @endif
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
                                    <img src="{{ asset('storage/' . $listing->images[0]->image_path) }}"
                                         alt="{{ $listing->aircraft_model }}" width="50">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $listing->user->first_name }}</td>
                            <td>
                                <a href="{{ route('messages.chat', ['user' => $listing->user->id]) }}"
                                   class="btn btn-primary btn-sm">Chat</a>
                            </td>
                            @if(auth()->user()->hasPermissionTo('listing.purchase') || auth()->user()->hasDirectPermissionTo('listing.purchase'))
                                <td>
                                    <form action="{{ route('listings.purchase', $listing->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Purchase</button>
                                    </form>
                                </td>
                            @endif

                            <td>
                                <a href="{{ route('listings.show', $listing->id) }}"
                                   class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('listings.edit', $listing->id) }}"
                                   class="btn btn-warning btn-sm">Edit</a>
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
