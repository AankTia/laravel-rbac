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
                    <td>
                        @if ($attribute === 'is_active')
                            {!! activeInactiveStatusBadgeFor($data['old_value']) !!}
                        @else
                            {{ $data['old_value'] }}
                        @endif
                    </td>
                    <td>
                        @if ($attribute === 'is_active')
                            {!! activeInactiveStatusBadgeFor($data['new_value']) !!}
                        @else
                            {{ $data['new_value'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
