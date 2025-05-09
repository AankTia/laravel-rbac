@if ($activity->action === 'created')
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th class="text-center">Attribute Name</th>
                <th class="text-center">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activity->properties['attributes'] as $attribute => $data)
            <tr>
                <td>{{ $attributeLabels[$attribute] ?? $attribute }}</td>
                <td>
                    @if ($activity->isRoleLog() && $attribute == 'allow_to_be_assigne')
                    {!! roleAllowToBeAssigneBadge($data) !!}
                    @else
                    {{ $data }}    
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@elseif($activity->action === 'updated')
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th class="text-center">Attribute Name</th>
                <th class="text-center">Old Value</th>
                <th class="text-center">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activity->properties['attributes'] as $attribute => $data)
            <tr>
                <td>{{ $attributeLabels[$attribute] ?? $attribute }}</td>
                <td>
                    @if ($activity->isRoleLog() && $attribute == 'allow_to_be_assigne')
                    {!! roleAllowToBeAssigneBadge($data['old']) !!}
                    @else
                    {{ $data['old'] }}    
                    @endif
                </td>
                <td>
                    @if ($activity->isRoleLog() && $attribute == 'allow_to_be_assigne')
                    {!! roleAllowToBeAssigneBadge($data['new']) !!}
                    @else
                    {{ $data['new'] }}    
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@elseif($activity->action === 'role-permission-updated')
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th class="text-center">Attribute Name</th>
                <th class="text-center">Old Value</th>
                <th class="text-center">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activity->properties['attributes'] as $attribute => $data)
            <tr>
                <td>{{ $attributeLabels[$attribute] ?? $attribute }}</td>
                <td>{{ $data['old'] }}</td>
                <td>{{ $data['new'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif