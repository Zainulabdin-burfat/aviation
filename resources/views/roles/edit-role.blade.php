@php
	$rolePermissions = $role->permissions->pluck('id')->toArray();
@endphp
@extends('layouts.main')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row g-4">
                <div class="mb-4">
                    <h3 class="role-title mb-2">Update Role</h3>
                    <p class="text-muted">Set role permissions</p>
                </div>
                <!-- Edit role form -->
                <form id="editRoleForm" class="row g-3" method="POST" action="{{ route('roles.update', ['role' => $role->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-4 mb-4">
                        <label class="form-label" for="role_name">Role Name</label>
                        <input required type="text" readonly disabled id="role_name" class="form-control" value="{{ $role->name }}" tabindex="-1" />
                    </div>
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <thead>
                                <tr>
                                    <th class="text-nowrap fw-medium">Module</th>
                                    <th class="text-nowrap fw-medium">Permissions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-nowrap fw-medium">Administrator Access
                                        <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows full access to the system"></i>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" />
                                            <label class="form-check-label" for="selectAll">Select All</label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($groupedPermissions as $module => $permission)
                                    <tr>
                                        <td class="text-nowrap fw-medium">{{$module}}
                                            <input class="form-check-input module-checkbox" type="checkbox" data-module="{{$module}}" />
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                @foreach($permission as $id => $name)
                                                    <div class="form-check me-1 me-lg-5 mb-1">
                                                        <input id="permissions" name="permissions[]" @if(in_array($id, $rolePermissions)) checked @endif value="{{$id}}" class="form-check-input permission-checkbox {{$module}}" type="checkbox" />
                                                        <label for="permissions" class="form-check-label">{{$name}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#selectAll').change(function () {
                $('.permission-checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.module-checkbox').change(function () {
                let module = $(this).data('module');
                if ($(this).prop('checked')) {
                    $('.' + module).prop('checked', true);
                } else {
                    $('.' + module).prop('checked', false);
                }
            });
        });
    </script>


    </div>

@endsection
