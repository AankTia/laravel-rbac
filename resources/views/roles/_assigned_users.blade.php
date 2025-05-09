<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="card-title m-0 me-2">Assigned Users</h5>
    </div>

    <div class="card-body">
        <hr class="mt-0">
        <div class="row">
            <div class="mb-2">
                <p>This role is currently assigned to <strong>{{ $role->getTotalUsers() }} users</strong> in the system.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th class="text-center">Assigned At</th>
                            <th class="text-center">Assigned By</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($role->roleUsers as $roleUser)
                        <tr>
                            <td class="fw-medium">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ $roleUser->user->initialName() }}
                                    </div>
                                    <div class="user-details">
                                        <p class="user-name"><a href="{{ route('users.show', $roleUser->user) }}">{{ $roleUser->user->name }}</a></p>
                                        <p class="user-email">{{ $roleUser->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-medium text-center" nowrap>{{ $roleUser->getFormatedAssignedAt() }}</td>
                            <td class="fw-medium text-center" nowrap>{{ $roleUser->getAssignedByName() }}</td>
                            <td class="fw-medium text-center">
                                @if (currentUserId() != $roleUser->id)
                                {!! deleteUserFromRoleButton() !!}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center"> No data to show</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>