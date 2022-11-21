@extends('layouts.tapp')
@section('content')
<div class="col-9">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@php
$eventid=0;
$stableidlist = [];
if(isset($_GET['SearchEventID'])){
    $eventid = intval($_GET['SearchEventID']);
}
if(isset($_GET['stablename'])){
    $sids= explode(',',$_GET['stablename']);
    foreach($sids as $sid){
        $indexlist = array_keys($stables,$sid);
        if(count($indexlist)>0){
            array_push($stableidlist,$indexlist[0]);
        }
    }
}
@endphp
<div class="mb-2">
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
<div class="mt-1 form-floating">
    <select class="stable-select select-2-basic form-select col-12 text-center fs-5" multiple="multiple" style="height:75px;" name="stableid" id="stableid">
        @foreach($stables as $k=>$v)
        <div>@php gettype($k) @endphp</div>
        @if(in_array($k,$stableidlist))
            <option id={{$k}} selected value={{$k}}><p>{{$v}}</p></option>
        @else
            <option id={{$k}} value={{$k}}><p>{{$v}}</p></option>
        @endif
        @endforeach
    </select>
</div>
@endif
</div>
    @php
        $titles= ['final'=>'Final List','pfa'=>'Pending for Acceptance','prov'=>'Provisional Entries','royprov'=>'Royal Provisional Entries','pfr'=>'Pending for Review','re'=>'Rejected/Withdrawn Entries'];
    @endphp

@if($eventid > 0 && isset($events[$eventid]))
@foreach (${Str::plural($modelName)} as $key => $lists)
<h1>{{Str::upper($titles[$key]). ' - Total Entries : ' }}{{count($lists)}}</h1>
<div class="table-responsive mt-2">
<table id={{$key}} class="table table-striped table-bordered table-responsive">
    <thead>
        <tr>
            <!-- <th>StartNo</th>
            <th class="export">Rider FName</th>
            <th class="export">Rider LName</th>
            <th>RIDER EEF</th>
            <th>RIDER FEI</th>
            <th>RiderNationality</th>
            <th>Horse</th>
            <th>HORSE EEF</th>
            <th>HORSE FEI</th>
            <th>Sex</th>
            <th>Colour</th>
            <th>Breed</th>
            <th>YOB</th>
            <th class="export">Horse Chip</th>
            <th class="export">Rider Weight</th>
            <th>Owner</th>
            <th>Trainer</th>
            <th>Stable</th>
            <th class="export">Division</th>
            <th class="export">TEAM</th>
            <th class="export">Transponsder Code</th>
            @if(!in_array($key,["re","pdf","pfr"])) -->
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
            <th class="export">QR</th>
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
</div>
@endforeach
<div class="row mb-2">
    <div class="col text-center fs-3"><h1>Overall Entries: {{$total}}</h1></div>
</div>
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
        // const copy = 'copy';
        // const csv = 'csv';
        // const excel = {extend:'excel',messageTop:null,messageBottom:null,title:null};
        // const pdf = 'pdf';
        // const print = 'print';
        // const dom = '<"container-fluid"<"row"<"col"<"col"l><"col"f>><"col"B>>>rtip';
        // const responsive = false;
        const d = JSON.parse('{!! json_encode((object)$events) !!}');
        // const f = JSON.parse('{!! json_encode((object)$eventnames) !!}');
        let urlParams = new URLSearchParams(window.location.search);
        const eventval = urlParams.get('SearchEventID');
        // let final = $('#final').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             final.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(Final List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
        // let pfa = $('#pfa').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             pfa.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(For Approval List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
        // let prov = $('#prov').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             prov.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(Provisional List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
        // let royprov = $('#royprov').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             royprov.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(Royal Provisional List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
        // let pfr = $('#pfr').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             pfr.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(For Review List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
        // let re = $('#re').DataTable({
        //     dom: dom,
        //     buttons:[
        //     {
        //         text: 'Show/Hide Export Data',
        //         action: function ( e, dt, node, config ) {
        //             re.columns('.export').visible(this.active());
        //             this.active(!this.active());
        //         }
        //     },copy,csv,{...excel, filename:`(Not Eligible List) ${f[eventval]}`},pdf,print],
        //     responsive:responsive
        // });
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
            allowClear:true
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

        const a= $.map($('#stableid').val(), function (obj) {
                return d[obj];
              });
       
        let urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('stablename')){
                if(a.length==0){
                    urlParams.delete('stablename');
                }else{
                    urlParams.set('stablename',a);
                }
            }else{
                urlParams.append('stablename',a);
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