<div class="table-responsive mb-5">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th class="text-center">Attribute Name</th>
                <th class="text-center">Old Value</th>
                <th class="text-center">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attributes as $attribute => $data)
                <tr>
                    <td>{{ $data['label'] }}</td>
                    @if ($attribute === 'is_active')
                        <td>{!! activeInactiveStatusBadgeFor($data['old_value']) !!}</td>
                        <td>{!! activeInactiveStatusBadgeFor($data['new_value']) !!}</td>
                    @elseif ($log_name === 'Role' && $attribute === 'allow_to_be_assigne')
                        <td>{!! roleAllowToBeAssigneBadge($data['old_value']) !!}</td>
                        <td>{!! roleAllowToBeAssigneBadge($data['new_value']) !!}</td>
                    @else
                        <td>{{ $data['old_value'] }}</td>
                        <td>{{ $data['new_value'] }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
