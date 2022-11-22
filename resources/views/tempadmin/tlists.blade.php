@extends('layouts.tapp')
@section('content')
    <div class="">
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
                    <table id={{$key}} class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="a-export m-export d-export">StartNo</th>
                                <th class="a-export m-export d-export">StartCode</th>
                                <th class="hide m-export d-export">Rider FName</th>
                                <th class="hide m-export d-export">Rider LName</th>
                                <th class="a-export">RiderName</th>
                                <th class="a-export m-export d-export">RiderEEF</th>
                                <th class="a-export m-export d-export">RiderFEI</th>
                                <th class="a-export m-export d-export">RiderNationality</th>
                                <th class="hide d-export">RiderGender</th>
                                <th class="a-export m-export d-export">HorseName</th>
                                <th class="a-export m-export d-export">HorseEEF</th>
                                <th class="a-export m-export d-export">HorseFEI</th>
                                <th class="a-export m-export d-export">Sex</th>
                                <th class="a-export m-export d-export">Colour</th>
                                <th class="a-export m-export d-export">Breed</th>
                                <th class="a-export m-export d-export">YOB</th>
                                <th class="hide d-export">HorseOrigin</th>
                                <th class="hide d-export">Horse Chip</th>
                                <th class="hide d-export">Rider Weight</th>
                                <th class="a-export m-export d-export">Owner</th>
                                <th class="a-export m-export d-export">Trainer</th>
                                <th class="a-export m-export d-export">Stable</th>
                                <th class="hide m-export d-export">Division</th>
                                <th class="hide m-export d-export">TEAM</th>
                                <th class="hide m-export d-export">Transponsder Code</th>
                                @if(!in_array($key,["re","pdf","pfr"]))
                                    <th class="d-export">QR</th>
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
                                    {{$entry->racestartcode ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->rfname ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->rlname ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->ridername ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->ridernfid ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->riderfeiid ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->rcountry ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->rgender ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->horsename ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->horsenfid ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->horsefeiid ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{ $entry->hgender ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{ $entry->color ?? 'N/A'}}
                                    </td> 
                                    <td class="text-center">
                                    {{$entry->breed ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                        {{date('Y', strtotime($entry->yob)) ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                        {{$entry->horigin ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    <strong>
                                    {{$entry->microchip ?? 'N/A'}}
                                    </strong>
                                    </td>
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                    {{$entry->ownername ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->trainername ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->stablename ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                    </td>
                                    <td class="text-center">
                                    {{$entry->startno ?? 'N/A'}}
                                    </td>
                                    @if(!in_array($key,["re","pdf","pfr"]))
                                    <td class="text-center">
                                    {{$entry->qrval ?? 'N/A'}}
                                    </td>
                                    @else
                                    <td class="text-center">
                                    {{$entry->remarks ?? 'N/A'}}
                                    </td>
                                    @endif
                                    <td class="text-center">
                                    {{$entry->status ?? 'N/A'}}
                                    </td>
                                    @if(!in_array($key,["re","pdf","pfr"]))
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
                const excel = {extend:'excel',messageTop:null,messageBottom:null,title:null};
                const pdf = {extend:'pdfHtml5',orientation:'landscape',pageSize:'A4',
                    messageTop:null,
                    messageBottom:null,
                    title:null,
                };
                const print = {extend:'print',footer:false,
                    messageTop:'',
                    messageBottom:'',
                    title:'',};
                const dom = '<"container-fluid"<"row"<"col"<"col"l><"col"f>><"col"B>>>rtip';
                const responsive = false;
                const d = JSON.parse('{!! json_encode((object)$events) !!}');
                const f = JSON.parse('{!! json_encode((object)$eventnames) !!}');
                let urlParams = new URLSearchParams(window.location.search);
                const eventval = urlParams.get('SearchEventID');
                let final = $('#final').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Final List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(Final List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(Final List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(Final List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
                let pfa = $('#pfa').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(For Acceptance List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(For Acceptance List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(For Acceptance List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(For Acceptance List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
                let prov = $('#prov').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Provisional List) ${f[eventval]}`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(Provisional List) ${f[eventval]}`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(Provisional List) ${f[eventval]}`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(Provisional List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
                let royprov = $('#royprov').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Royal Provisional List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(Royal Provisional List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(Royal Provisional List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(Royal Provisional List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
                let pfr = $('#pfr').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(For Review List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(For Review List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(For Review List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(For Review List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
                let re = $('#re').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Not Qualified List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(Not Qualified List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(Not Qualified List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
                        {...pdf, filename:`(Not Qualified List) ${f[eventval]}`, 
                        className:'aexport',text:'PDF',exportOptions:{columns:'.a-export'},
                            customize : function(doc){
                                console.log(doc);
                                doc.fontSize =8;
                                doc.pageMargins= 5;
                                doc.content[0].table.widths = Array.from({length: $('.a-export').length/5}, (_, i) => 'auto');
                                // doc.content[0].table.widths=Array(doc.content[0].table.body[0].length + 1).join('%').split('');
                            }
                        },
                        {...print,exportOptions:{columns:'.a-export'}}
                    ],
                    responsive:responsive,
                    columnDefs: [
                        {
                            target: 'hide',
                            visible: false,
                            searchable: false,
                        },
                    ]
                });
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