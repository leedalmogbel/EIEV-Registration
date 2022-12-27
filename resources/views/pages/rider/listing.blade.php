<div class="table-responsive">
    <table id="rider-listing" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Stable</th>
                {{-- <th>Status</th>
            <th width="100" style="text-align:right">ACTIONS</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($eef_riders as $rider)
                <tr>
                    <td>{{ $$modelName->firstx0020name }} {{ $$modelName->familyx0020name }}</td>
                    <td>{{ $$modelName->email }}</td>
                    <td>{{ $$modelName->stable }}</td>
                    {{-- <td>@include('partials.status', ['status' => $$modelName->status])</td>
            <td style="text-align: right">@include('partials.actions', ['object' => $$modelName])</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#rider-listing').DataTable();
    });
</script>
