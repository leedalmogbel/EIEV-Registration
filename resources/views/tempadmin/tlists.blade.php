@extends('layouts.tapp')
@section('content')
<div class="col-9">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@php
$eventid=0;
$stableid = -1;
if(isset($_GET['SearchEventID'])){
    $eventid = intval($_GET['SearchEventID']);
}
if(isset($_GET['stablename'])){
    $indexlist = array_keys($stables,$_GET['stablename']);
    if(count($indexlist)>0){
        $stableid = $indexlist[0];
    }
}
@endphp
<div class="mt-3 form-floating">
    <label for="eventid">Select a Ride</label>
    <select class="select-2-basic form-select col-12 text-center fs-5" style="height:75px;" name="eventid" id="eventid">
        <option disabled selected value="defval">Event ID : EVENT NAME | EVENT DATE | OPENING | CLOSING</option>
        @foreach($events as $k => $v)
        <div>@php gettype($k) @endphp</div>
        @if($eventid > 0 && $eventid == intval($k))
            <option id={{$k}} selected value={{$k}}><p>{{$v}}</p></option>
        @else
            <option id={{$k}} value={{$k}}><p>{{$v}}</p></option>
        @endif
        @endforeach
    </select>
</div>
@if(count($stables)>0)
<div class="mb-5 mt-1 form-floating">
    <select class="stable-select select-2-basic form-select col-12 text-center fs-5" style="height:75px;" name="stableid" id="stableid">
        <option disabled selected value="defval">All</option>
        @foreach($stables as $k=>$v)
        <div>@php gettype($k) @endphp</div>
        @if($stableid > -1 && $stableid == intval($k))
            <option id={{$k}} selected value={{$k}}><p>{{$v}}</p></option>
        @else
            <option id={{$k}} value={{$k}}><p>{{$v}}</p></option>
        @endif
        @endforeach
    </select>
</div>
@endif
    @php
        $titles= ['final'=>'Final List','pfa'=>'Pending for Acceptance','prov'=>'Provisional Entries','royprov'=>'Royal Provisional Entries','pfr'=>'Pending for Review','re'=>'Rejected/Withdrawn Entries'];
    @endphp
@if($eventid > 0 && isset($events[$eventid]))
@foreach (${Str::plural($modelName)} as $key => $lists)
<h1>{{Str::upper($titles[$key]). ' - Total Entries : ' }}{{count($lists)}}</h1>
<table id={{$key}} class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>START NO</th>
            <th>STABLE</th>
            <th>OWNER</th>
            <th>TRAINER</th>
            <th>HORSE</th>
            <td>EEF ID|FEI ID</td>
            <th>GENDER</th>
            <th>COLOR</th>
            <th>YOB</th>
            <th>RIDER</th>
            <th>EEF ID|FEI ID</th>
            <th>GENDER</th>
            @if(!in_array($key,["re","pdf","pfr"]))
            <th>QR</th>
            @else
            <th>Remarks</th>
            @endif
            <th>Status</th>
            @if(!in_array($key,["re","pdf","pfr"]))
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
                <td class="text-center">
                {{$entry->stablename ?? 'N/A'}}
                </td>
                <td class="text-center">
                {{$entry->ownername ?? 'N/A'}}
                </td>
                <td class="text-center">
                {{$entry->trainername ?? 'N/A'}}
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
                @if(!in_array($key,["re","pdf","pfr"]))
                <td class="text-center">
                {{$entry->qrval ?? 'UNK'}}
                </td>
                @else
                <td class="text-center">
                {{$entry->remarks ?? 'UNK'}}
                </td>
                @endif
                <td class="text-center">
                {{$entry->status ?? 'UNK'}}
                @if(!in_array($key,["re","pdf","pfr"]))
                </td>
                  <td class="text-center">
                    <div>
                        <a href="/rideslist/reject?entrycode={{ $entry->code }}" class="btn btn-danger" id="reject-entry"><i
                                class="fa-solid fa-close"></i></a>
                        @if(!Str::contains($key,'final'))
                        @if(in_array($key,['prov','royprov']))
                        <a href="/rideslist/mainlist?entrycode={{ $entry->code }}" class="btn btn-success" id="move-entry"><i
                                class="fa-solid fa-check"></i></a>
                        @else
                        <a href="/rideslist/accept?entrycode={{ $entry->code }}" class="btn btn-success" id="accept-entry"><i
                                class="fa-solid fa-check"></i></a>
                        @endif
                        @endif
                        <a href="/rideslist/withdraw?entrycode={{ $entry->code }}" class="btn btn-main" id="withdraw-entry"><i
                                class="fa-solid fa-eject"></i></a>
                    </div>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endforeach
@endif
</div>
<script type="text/javascript">
    $(document).ready(function(e) {
        $('#final').DataTable();
        $('#pfa').DataTable();
        $('#prov').DataTable();
        $('#royprov').DataTable();
        $('#pfr').DataTable();
        $('#re').DataTable();
        const d = JSON.parse('{!! json_encode((object)$events) !!}');
        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('SearchEventID')){
            const val = urlParams.get('SearchEventID');
            if(d[val]!==undefined){
                $('#eventid').val(urlParams.get('SearchEventID'))
            }else{
                $('#eventid').val("defval")
                urlParams.delete("SearchEventID")

            }
        }else{
            $('#eventid').val("defval")
            urlParams.delete("SearchEventID")

        }
        $('.stable-select.select-2-basic').select2({
            placeholder: "Filter by Stable",
        });
    });
</script>
<script>
    $('#eventid').on('change',function(e)
    {
        const eid = e.target.value;
        
        let urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('SearchEventID')){
            urlParams.set('SearchEventID',eid);
        }else{
            urlParams.append('SearchEventID',eid);
        }
        window.location.search = urlParams;

    });
    $('#stableid').on('change',function(e)
    {
        const eid = e.target.value;
        const d = JSON.parse('{!! json_encode((object)$stables) !!}');
        let urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('stablename')){
                if(d[eid] == "All"){
                    urlParams.delete('stablename');
                }else{
                    urlParams.set('stablename',d[eid]);
                }
            }else{
                urlParams.append('stablename',d[eid]);
            }
            window.location.search = urlParams;
        
        

    });
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
    $(document).on('click', '#move-entry', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Move Entry to Main List',
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