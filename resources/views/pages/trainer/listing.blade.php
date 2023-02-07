<div class="table-responsive">
    <table id="trainer-list" class="table table-striped table-bordered">
        <tr>
            <th>Name</th>
            <th>Stable</th>
            <th>Email</th>
            {{-- <th>Status</th>
        <th width="100" style="text-align:right">ACTIONS</th> --}}
        </tr>
        @foreach ($eef_trainers as $trainer)
            <tr>
                <td>{{ $trainer->firstx0020name }} {{ $trainer->familyx0020name }}</td>
                <td>{{ $trainer->stable }}</td>
                <td>{{ $trainer->email }}</td>
                {{-- <td>@include('partials.status', ['status' => $trainer->status])</td>
            <td style="text-align: right">@include('partials.actions', ['object' => $trainer])</td> --}}
            </tr>
        @endforeach
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#trainer-listing').DataTable();
    });
</script>
