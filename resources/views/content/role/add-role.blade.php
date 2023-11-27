<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Add New Role</h3>
                    <p class="text-muted">Set role permissions</p>
                </div>
                <!-- Add role form -->
                <form id="addRoleForm" class="row g-3" method="POST" action="{{ route('role.store') }}">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="role_name">Role Name</label>
                        <input required type="text" id="role_name" name="role_name" class="form-control"
                               placeholder="Enter a role name" tabindex="-1"/>
                    </div>
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <tbody>
                                <tr>
                                    <td class="text-nowrap fw-medium">Administrator Access
                                        <i class="ti ti-info-circle"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Allows a full access to the system"></i>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll"/>
                                            <label class="form-check-label" for="selectAll">
                                                Select All
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                @foreach($groupedPermissions as $module => $permission)
                                    <tr>
                                        <td class="text-nowrap fw-medium">{{$module}} <input
                                                class="form-check-input module-checkbox" type="checkbox"
                                                data-module="{{$module}}"/></td>

                                        <td>
                                            <div class="d-flex flex-wrap">
                                                @foreach($permission as $id => $name)
                                                    <div class="form-check me-1 me-lg-5 mb-1">
                                                        <input name="permissions[]" value="{{$id}}"
                                                               class="form-check-input permission-checkbox {{$module}}"
                                                               type="checkbox"/>
                                                        <label class="form-check-label"> {{$name}} </label>
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
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add Role Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
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
