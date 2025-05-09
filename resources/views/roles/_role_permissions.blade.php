<div class="card shadow-sm mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Allowed Permissions</h5>
        @if(isUserCan('update-role-permissions', 'role'))
        {!! editButton(route('roles.edit-permissions', $role), 'Update Permissions') !!}
        @endif
    </div>

    <div class="card-body">
        <hr class="mt-0">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Module</th>
                            <th class="text-center">Read</th>
                            <th class="text-center">Create</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                            <th class="text-center">Special Privileges</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modulePermissions as $moduleName => $permissions)
                        <tr>
                            <td class="fw-medium">{{ $moduleName }}</td>
                            <td class="text-center">
                                @if ($permissions['read'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($permissions['create'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($permissions['update'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($permissions['delete'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td nowrap>
                                @foreach ($permissions['others'] as $otherPermission)
                                <div>
                                    <i class="bx bxs-check-circle text-success"></i> {{ $otherPermission }}
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>