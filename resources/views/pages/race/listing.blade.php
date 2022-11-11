<table class="table table-striped table-bordered">
    <tr>
        <th width="300">Race</th>
        <th>Event</th>
        <th>Contact</th>
        <td>Race Dates</td>
        <th>STATUS</th>
        <th width="100" style="text-align:right">ACTIONS</th>
    </tr>
    {{-- @foreach (${Str::plural($modelName)} as $race) --}}
    @foreach ($eef_events as $race)
        <tr>
            <td>
                <div><a href="/entry/create?raceid={{ $race->raceid }}"
                        rel="noopener noreferrer">{{ $race->racename }}</a></div>
            </td>
            <td>{{ $race->racelocation }}</td>
            <td>
                {{-- <div>{{ $race->contact['person'] }}</div>
                <div><small class="text-success">{{ $race->contact['number'] }}</small></div> --}}
            </td>
            <td>
                <div>Race Date: <strong>{{ date('M d, Y', strtotime($race->racetodate)) }}</strong></div>
                <div>From Date: <strong>{{ date('M d, Y', strtotime($race->racefromdate)) }}</strong></div>
                <div>To Date: <strong>{{ date('M d, Y', strtotime($race->racetodate)) }}</strong></div>
            </td>
            {{-- <td>
                @include('partials.status', ['status' => $race->status])
            </td>
            <td style="text-align: right">
                @include('partials.actions', [
                    'object' => $race,
                    'actions' => [
                        '<i class="fa-solid fa-check"></i> Add Entry' => [
                            'href' => '/entry/create?race_id=' . $race->race_id,
                            'class' => 'boom-panes',
                        ],
                    ],
                ])
            </td> --}}
        </tr>
    @endforeach
</table>
