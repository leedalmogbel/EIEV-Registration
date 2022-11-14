<table class="table table-striped table-bordered">
    <tr>
        <th width="300">Race / Event</th>
        <th>User</th>
        <th>Horse</th>
        <th>Rider</th>
        <th>Status</th>
        <th width="100" style="text-align:right">ACTIONS</th>
    </tr>
    @foreach ($eef_entries as $entry)
        {{-- @foreach (${Str::plural($modelName)} as $entry) --}}
        <tr>
            <td>
                <div>{{ $entry->race->title }}</div>
                <div class="text-secondary">{{ $entry->race->event->name }}</div>
            </td>
            <td>
                {{ $entry->user->firstname }} {{ $entry->user->lastname }}
            </td>
            <td>
                <div>{{ $entry->horse->name }}</div>
                <div class="text-secondary">{{ $entry->horse->originalName }}</div>
            </td>
            <td>
                {{ $entry->rider->firstname }} {{ $entry->rider->lastname }}
            </td>
            <td>
                @include('partials.status', ['status' => $entry->status])
            </td>
            <td style="text-align: right">
                @include('partials.actions', ['object' => $entry])
            </td>
        </tr>
    @endforeach
</table>
