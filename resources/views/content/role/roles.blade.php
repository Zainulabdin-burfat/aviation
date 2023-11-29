@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Roles')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}"/>
@endsection

@section('vendor-script')
    <script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
{{--    <script src="{{asset('assets/js/app-access-roles.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/modal-add-role.js')}}"></script>--}}
    <script>
        $(function () {
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
    <h4 class="mb-4">Roles List</h4>

    <p class="mb-4">A role provided access to predefined menus and features so that depending on <br> assigned role an
        administrator can have access to what user needs.</p>
    <!-- Role cards -->
    <div class="row g-4">

        @forelse($roles as $role)
            @if($role->id == 1)
                @continue
            @endif
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mt-1">
                            <div class="role-heading">
                                <h4 class="mb-1">{{$role->name}}</h4>
                                <a href="{{ route('role.edit', ['role' => $role->id]) }}"
                                   class="role-edit-modal"><span>Edit Role</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No Roles Available..!</p>
        @endforelse

        @include('content.role.add-role')

    </div>

@endsection
