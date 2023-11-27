@extends('layouts.main')

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
                                <a href="{{ route('roles.edit', ['role' => $role->id]) }}"
                                   class="role-edit-modal"><span>Edit Role</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No Roles Available..!</p>
        @endforelse

    </div>

@endsection
