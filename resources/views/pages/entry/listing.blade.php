<table class="table table-striped table-bordered">
    <tr>
        <th>Horse</th>
        <th>Rider</th>
        <th>Trainer</th>
        <th>Stable</th>
        <th>Status</th>
        <th width="100" style="text-align:right">ACTIONS</th>
    </tr>
    @foreach ($eef_entries as $entry)
        {{-- @foreach (${Str::plural($modelName)} as $entry) --}}
        <tr>
            <td>
                <div>{{ $entry->horsename }}</div>
                <div class="text-secondary">{{ $entry->horsenfid }}</div>
                <div class="text-secondary">{{ $entry->horsefeiid }}</div>
            </td>
            <td>
                <div>{{ $entry->ridername }}</div>
                <div class="text-secondary">{{ $entry->ridernfid }}</div>
                <div class="text-secondary">{{ $entry->riderfeiid }}</div>

            </td>
            <td>
                <div>{{ $entry->trainername }}</div>
                <div class="text-secondary">{{ $entry->trainernfid }}</div>
                <div class="text-secondary">{{ $entry->trainerfeiid }}</div>
            </td>
            <td>
                {{ $entry->stablename }}
            </td>
            <td>
                {{-- @include('partials.status', ['status' => $entry->status]) --}}
                {{ $entry->status }}
            </td>
            <td>
                {{-- @include('partials.actions', ['object' => $entry]) --}}
                <div>
                    @if ($entry->status == 'Eligible' || $entry->status == 'Accepted')
                        <a id="withdrawn"
                            href="/entry/withdrawn?raceid={{ $entry->eventcode }}&entrycode={{ $entry->code }}&status=withdrawn"
                            class="btn btn-danger">
                            <i class="fa-solid fa-eject"></i>
                            Withdrawn
                        </a>
                    @else
                        No Withdrawn Required
                    @endif
                    {{-- <a href="/entry?raceid={{ $race->raceid }}" class="btn btn-main" id="view-entry"><i
							class="fa-regular fa-eye"></i> View Entry</a> --}}
                </div>
            </td>
        </tr>
    @endforeach
</table>
<script type="text/tpl" id="swap-form">
	<p>Are you sure to withdraw?</p>
</script>
<script>
    $(document).on('click', '#withdrawn', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Withdrawn Entry',
            columnClass: 'col-md-8',
            content: $('#swap-form').html(),
            buttons: {
                'I Agree': {
                    btnClass: 'btn-main',
                    action: function() {
                        window.location.href = href
                    }
                },
                'I Disagree': {
                    btnClass: 'btn-danger',
                    action: function() {

                    }
                }
            }
        });
    });
</script>
