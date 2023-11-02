@extends('layouts.tapp')
@section('content')
    <div class="table-responsive">
        <table id="media-list" class="table table-striped table-bordered">
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>EID</th>
                <th>Photo</th>
                <th>Company</th>
                <th>Date Registered</th>
                {{-- <th>Status</th>
        <th width="100" style="text-align:right">ACTIONS</th> --}}
            </tr>
            @foreach ($lists as $list)
                <tr>
                    <td>{{ $list->firstname }} {{ $list->lastname }}</td>
                    <td>{{ $list->mobile }}</td>
                    <td>{{ $list->email }}</td>
                    <td>{{ $list->emirates_id }}</td>
                    <td>{{ $list->company }}</td>
                    <td><img width=100 height=100 src="{{ $list->photo }}" /></td>
                    <td>{{ $list->created_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
<script type="text/javascript">
    $(document).ready(function() {
        $('#media-listing').DataTable();
    });
</script>
