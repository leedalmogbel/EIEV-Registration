@extends('layouts.tapp')
@section('content')
<div class="col-9">
@foreach (${Str::plural($modelName)} as $key => $lists)
<h1>{{Str::title($key). ' Entries - ' }}{{count($lists)}}</h1>
<table id={{$key}} class="table table-striped table-bordered">
    <thead>
        <tr>
            <!-- <th width="300">Race</th> -->
            <th>START NO</th>
            <th>HORSE</th>
            <td>EEF ID|FEI ID</td>
            <th>GENDER</th>
            <th>COLOR</th>
            <th>YOB</th>
            <th>RIDER</th>
            <th>EEF ID|FEI ID</th>
            <th>GENDER</th>
            <th>QR</th>
            <th>Status</th>
          <th width="100" style="text-align:right">ACTIONS</th> 
        </tr>
    </thead>
    <tbody>
        @foreach ($lists as $entry)
            <tr>
                <td class="text-center">
                {{$entry->startno ?? 'N/A'}}
                </td>
                <td class="text-center"><strong>{{ $entry->horsename }}</strong></td>
                <td class="text-center">
                  <div>{{ $entry->horsenfid }}</div>
                  <div>{{ $entry->horsefeiid }}</div>
                </td> 
                <td class="text-center">
                {{$entry->hgender ?? 'UNK'}}
                </td>
                <td class="text-center">
                {{$entry->color ?? 'UNK'}}
                </td>
                <td class="text-center">
                {{date('Y-m-d', strtotime($entry->yob)) ?? 'UNK'}}
                </td>
                <td class="text-center">
                  <strong>
                {{$entry->ridername}}
                  </strong>
                </td>
                <td class="text-center">
                  <div>{{ $entry->ridernfid }}</div>
                  <div>{{ $entry->riderfeiid }}</div>
                </td>
                <td class="text-center">
                {{$entry->rgender ?? 'UNK'}}
                </td>
                <td class="text-center">
                {{$entry->qrval ?? 'UNK'}}
                </td>
                <td class="text-center">
                {{$entry->status ?? 'UNK'}}
                </td>
                  <td class="text-center">
                    <div>
                        <a href="/rideslist/reject?entrycode={{ $entry->code }}" class="btn btn-danger" id="reject-entry"><i
                                class="fa-solid fa-close"></i></a>
                        @if(!Str::contains($key,'final'))
                        <a href="/rideslist/accept?entrycode={{ $entry->code }}" class="btn btn-success" id="accept-entry"><i
                                class="fa-solid fa-check"></i></a>
                        @endif
                        <a href="/rideslist/withdraw?entrycode={{ $entry->code }}" class="btn btn-main" id="withdraw-entry"><i
                                class="fa-solid fa-eject"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endforeach
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#final').DataTable();
        $('#main').DataTable();
        $('#private').DataTable();
        $('#royal').DataTable();
    });
</script>
<script>
    $(document).on('click', '#reject-entry', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Reject Entry',
            columnClass: 'col-md-8',
            content: $('#swap-form').html(),
            buttons: {
                'Yes': {
                    btnClass: 'btn-main',
                    action: function() {
                        window.location.href = href
                    }
                },
                'No': {
                    btnClass: 'btn-danger',
                    action: function() {

                    }
                }
            }
        });
    });
    $(document).on('click', '#accept-entry', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Accept Entry',
            columnClass: 'col-md-8',
            content: $('#swap-form').html(),
            buttons: {
                'Yes': {
                    btnClass: 'btn-main',
                    action: function() {
                        window.location.href = href
                    }
                },
                'No': {
                    btnClass: 'btn-danger',
                    action: function() {

                    }
                }
            }
        });
    });
    $(document).on('click', '#withdraw-entry', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Withdraw Entry',
            columnClass: 'col-md-8',
            content: $('#swap-form').html(),
            buttons: {
                'Yes': {
                    btnClass: 'btn-main',
                    action: function() {
                        window.location.href = href
                    }
                },
                'No': {
                    btnClass: 'btn-danger',
                    action: function() {

                    }
                }
            }
        });
    });
</script>
@endsection