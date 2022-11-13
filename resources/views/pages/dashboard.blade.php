@extends('partials.frame')

@section('content')
    <div class="content container col-9 align-items-center justify-content-center">
        <div class="row counts py-5">
            <div class="col-md-4">
                <div class="card image-container-horses">
                    <div class="card-block p-3 text-center">
                        <h4 class="card-title">Horses</h4>
                        <h6 class="card-subtitle text-muted">{{ $dashcount->horses }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card image-container-riders">
                    <div class="card-block p-3 text-center">
                        <h4 class="card-title">Riders</h4>
                        <h6 class="card-subtitle text-muted">{{ $dashcount->riders }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card image-container-trainers">
                    <div class="card-block p-3 text-center">
                        <h4 class="card-title">Trainers</h4>
                        <h6 class="card-subtitle text-muted">{{ $dashcount->trainers }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- START: UPCOMING LIST --}}
        <div class="row upcoming-list">
            <div class="col">
                <div class="upcoming-list__wrapper">
                    <h4>Upcoming Rides</h4>
                    {{-- START: UPCOMING LIST --}}
                    @if ($events)
                        @foreach ($events as $event)
                            <div class="list-group">
                                <div class="col py-1">
                                    <a href="/entry/create?raceid={{ $event->raceid }}">
                                        <div class="card">
                                            <div class="card-block p-3">
                                                <h4 class="card-title">{{ $event->racename }}</h4>
                                                <h6 class="card-title">{{ $event->racelocation }} {{ $event->racecountry }}
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        No Event
                    @endif
                    {{-- END: END LIST --}}
                </div>
            </div>
        </div>
        {{-- END: UPCOMING LIST --}}

        {{-- START: RECENT ENTRIES --}}
        <div class="row recent-entries">
            <div class="col">
                <div class="recent-entries__wrapper">
                    <h4>Recent Entries</h4>
                    {{-- START: RECENT ENTRIES --}}
                    @if ($entries)
                        <table id="recentEntries" class="table table-striped table-bordered" style="width:100%">
                            <tr>
                                <th>#</th>
                                <th>Horse</th>
                                <th>Rider</th>
                                <th>Trainer</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                            @foreach ($entries as $entry)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        {{ $entry->horsename }}
                                        {{ $entry->horsenfid }}
                                        {{ $entry->horsefeiid }}
                                    </td>
                                    <td>
                                        {{ $entry->ridername }}
                                        {{ $entry->ridernfid }}
                                        {{ $entry->riderfeiid }}
                                    </td>
                                    <td>
                                        {{ $entry->trainername }}
                                        {{ $entry->trainernfid }}
                                        {{ $entry->trainerfeiid }}
                                    </td>
                                    <td>{{ $entry->status }}</td>
                                    <td>{{ $entry->remarks }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        No Entries
                    @endif
                    {{-- END: RECENT ENTRIES --}}
                </div>
            </div>
        </div>
        {{-- END: RECENT ENTRIES --}}

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#recentEntries').DataTable();
        });
    </script>
@endsection
