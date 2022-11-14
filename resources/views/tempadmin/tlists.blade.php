@extends('layouts.tapp')
@section('content')
<div class="col-9">
@foreach (${Str::plural($modelName)} as $key => $lists)
<h1>{{Str::upper(Str::plural($key)). ' - ' }}{{count($lists)}}</h1>
<table id={{$key}} class="table table-striped table-bordered">
    <thead>
        <tr>
            <!-- <th width="300">Race</th> -->
            <th>START NO</th>
            <th>HORSE</th>
            <td>EEF ID | FEI ID</td>
            <th>GENDER</th>
            <th>COLOR</th>
            <th>YOB</th>
            <th>RIDER</th>
            <th>EEF ID | FEI ID</th>
            <th>GENDER</th>
            <th>QR</th>
          @if (!Str::contains($key,'final'))
          <th width="100" style="text-align:right">ACTIONS</th> 
          @endif
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
                @if (!Str::contains($key,'final'))
                  <th width="100" style="text-align:right">ACTIONS</th> 
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endforeach
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#final').DataTable();
        $('#pending').DataTable();
        $('#royal').DataTable();
    });
</script>
@endsection