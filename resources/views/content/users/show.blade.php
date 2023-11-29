@extends('layouts/layoutMaster')

@section('title', 'User View - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-user-view.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">User / View /</span> Account
    </h4>
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ asset('assets/img/avatars/avatar.png') }}"
                                height="100" width="100" alt="User avatar" />
                            <div class="user-info text-center">
                                <h4 class="mb-2">{{ $user->first_name . ' ' . $user->last_name }}</h4>
                                <span class="badge bg-label-secondary mt-1">{{isset($user->roles[0]->name) ? $user->roles[0]->name : 'Super Admin'}}</span>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 small text-uppercase text-muted">Details</p>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="fw-medium me-1">First Name:</span>
                                <span>{{ $user->first_name }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Last Name:</span>
                                <span>{{ $user->last_name ?? '-' }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Status:</span>
                                @if ($user->status)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-danger">Inactive</span>
                                @endif

                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Role:</span>
                                <span>{{isset($user->roles[0]->name) ? $user->roles[0]->name : 'Super Admin'}}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-medium me-1">Phone:</span>
                                <span>{{ $user->phone ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /User Card -->

        </div>
        <!--/ User Sidebar -->
    </div>

@endsection
