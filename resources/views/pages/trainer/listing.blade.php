<table class="table table-striped table-bordered">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Visa</th>
        <th>Status</th>
        <th width="100" style="text-align:right">ACTIONS</th>
    </tr>
    @foreach ($eef_trainers as $trainer)
        <tr>
            <td>{{ $trainer->first_x0020_name }} {{ $trainer->family_x0020_name }}</td>
            <td>{{ $trainer->stable }}</td>
            <td>{{ $trainer->email }}</td>
            {{-- <td>@include('partials.status', ['status' => $trainer->status])</td>
            <td style="text-align: right">@include('partials.actions', ['object' => $trainer])</td> --}}
        </tr>
    @endforeach
</table>
