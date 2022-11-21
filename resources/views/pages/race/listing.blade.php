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
                <td>
                    <div><small
                            class={{ $race->statusname == 'Pending' ? 'text-danger' : 'text-success' }}>{{ $race->statusname }}</small>
                    </div>
                </td>
                <td>
                    <div>
                        @php
                            $createRace = '#';
                            $viewRace = '#';
                            if ($race->statusid == 11) {
                                $createRace = '/entry/create?raceid=' . $race->raceid;
                                $viewRace = '/entry?raceid=' . $race->raceid;
                            }
                            $statusclass = '';
                            $statuslabel = '';
                            if ($race->statusid == 11) {
                                $statusclass = 'text-success';
                                $statuslabel = 'Open for Entries';
                            } elseif ($race->statusid == 1) {
                                $statusclass = 'text-pending';
                                $statuslabel = 'Pending';
                            } else {
                                $statusclass = 'text-danger';
                                $statuslabel = 'Closed';
                            }
                        @endphp
                        @if ($race->statusid == 11)
                            <a href="/entry/create?raceid={{ $createRace }}" class='btn btn-main' id="add-entry"><i
                                    class="fa-solid fa-plus"></i> Add Entry</a>
                            <a href="/entry?raceid={{ $viewRace }}" class='btn btn-main' id="view-entry"><i
                                    class="fa-regular fa-eye"></i> View Entry</a>
                        @else
                            <a href="{{ $createRace }}" class='btn btn-danger disabled'id="add-entry"><i
                                    class="fa-solid fa-plus"></i> Add Entry</a>
                            <a href="{{ $viewRace }}" class='btn btn-danger disabled' id="view-entry"><i
                                    class="fa-regular fa-eye"></i> View Entry</a>
                        @endif
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
