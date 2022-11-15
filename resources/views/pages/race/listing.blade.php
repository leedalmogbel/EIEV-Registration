<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="300">Race</th>
            <th>Location</th>
            {{-- <th>Contact</th> --}}
            <td>Race Dates</td>
            <td>Status</td>
            <th>Actions</th>
            {{-- <th width="100" style="text-align:right">ACTIONS</th> --}}
        </tr>
    </thead>
    {{-- @foreach (${Str::plural($modelName)} as $race) --}}
    <tbody>
        @foreach ($eef_events as $race)
            <tr>
                <td>
                    <div>{{ $race->racename }}</div>
                </td>
                <td>{{ $race->racelocation }}</td>
                {{-- <td>
                <div>{{ $race->contact['person'] }}</div>
                <div><small class="text-success">{{ $race->contact['number'] }}</small></div>
            </td> --}}
                <td>
                    <div>Race Date: <strong>{{ date('M d, Y', strtotime($race->racetodate)) }}</strong></div>
                    <div>From Date: <strong>{{ date('M d, Y', strtotime($race->racefromdate)) }}</strong></div>
                    <div>To Date: <strong>{{ date('M d, Y', strtotime($race->racetodate)) }}</strong></div>
                </td>
                <td><div><small class={{$race->statusname == "Pending" ? 'text-danger' :'text-success'}}>{{$race->statusname}}</small></div></td>
                <td>
                    <div>
                        <a href="/entry/create?raceid={{ $race->raceid }}" class={{$race->statusname == "Pending" ? 'btn btn-dange disabled' :'btn btn-main'}} id="add-entry"><i
                                class="fa-solid fa-plus"></i> Add Entry</a>
                        <a href="/entry?raceid={{ $race->raceid }}" class={{$race->statusname == "Pending" ? 'btn btn-dange disabled' :'btn btn-main'}} id="view-entry"><i
                                class="fa-regular fa-eye"></i> View Entry</a>
                    </div>
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
    </tbody>
</table>
