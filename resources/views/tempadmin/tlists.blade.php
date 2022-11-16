@extends('layouts.tapp')
@section('content')
<div class="col-9 py-5">

@if(count(${Str::plural($modelName)})>0)
@foreach (${Str::plural($modelName)} as $key => $lists)
<table id={{$key}} class="table table-striped table-bordered py-5">
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
            @if(!in_array($key,["re","pdf","pfa"]))
            <th>QR</th>
            @else
            <th>Remarks</th>
            @endif
            <th>Status</th>
            @if(!in_array($key,["re","pdf","pfa"]))
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
                @if(!in_array($key,["re","pdf","pfa"]))
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
                @if($key!= "re")
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
@else
<div>NO DATA</div>

@endif
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#final').DataTable({
            dom: '<"#final.pfatoolbar">lftipr',
        });
        $('#pfa').DataTable({
            dom: '<"#pfa.pfatoolbar">lftipr',
        });
        $('#prov').DataTable({
            dom: '<"#prov.pfatoolbar">lftipr',
        });
        $('#royprov').DataTable({
            dom: '<"#royprov.pfatoolbar">lftipr',
        });
        $('#pfr').DataTable({
            dom: '<"#pfr.pfatoolbar">lftipr',
        });
        $('#re').DataTable({
            dom: '<"#re.pfatoolbar">lftipr',
        });
        const urlParams = new URLSearchParams(window.location.search);

        let titles= {
            'final':'Final List',
            'pfa':'Pending for Acceptance',
            'prov':'Provisional Entries',
            'royprov':'Royal Provisional Entries',
            'pfr':'Pending for Review',
            're':'Rejected/Withdrawn Entries'};
        if(!urlParams.has('presidentcup')){
            delete titles['royprov'];
        }
        const tkeys = Object.keys(titles);
        let dataobj = '{!! json_encode($entries) !!}';
        console.log(JSON.parse(dataobj));
        for(let k of tkeys){
            console.log(k,titles[k]);
            console.log(dataobj[k]);
            if(dataobj[k]!= undefined){
                if(k != "pfa"){
                    $(`div#${k}`).html(`<h1>${titles[k]} Total Entries : ${dataobj[k].data.length}</h1>`);
                }
                if(k == "pfa"){
                    if(dataobj[k].data.length>0){
                        $(`div#${k}`).html(`<h1>${titles[k]} Total Entries : ${dataobj[k].data.length}</h1>`);
                    }else{
                        $(`div#${k}`).html(`<h1>${titles[k]} Total Entries : ${dataobj[k].data.length}</h1>`);
                    }
                }
            }
        }
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