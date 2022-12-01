@extends('layouts.tapp')
@section('content')
    <style>
        .shide{
            display: none;
        }
        .uhide{
            display: none;
        }
        .bhide{
            display: none;
        }
    </style>
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
                    if(isset($stables[$sid])){
                        array_push($stableidlist,$sid);
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
                                <th class="a-elim a-export m-export d-export">serial</th>
                             
                                <th>EntryCode</th>
                                <th>UserID</th>
                                <th class="a-elim a-export m-export d-export">StartNo</th>
                                <th class="a-elim a-export m-export d-export">StartCode</th>
                                <th class="hide m-export d-export">Rider FName</th>
                                <th class="hide m-export d-export">Rider LName</th>
                                <th class="a-elim a-export">RiderName</th>
                                <th class="a-elim a-export m-export d-export">RiderEEF</th>
                                <th class="a-elim a-export m-export d-export">RiderFEI</th>
                                <th class="a-elim a-export m-export d-export">RiderNationality</th>
                                <th class="hide d-export">RiderGender</th>
                                <th class="a-elim a-export m-export d-export">HorseName</th>
                                <th class="a-elim a-export m-export d-export">HorseEEF</th>
                                <th class="a-elim a-export m-export d-export">HorseFEI</th>
                                <th class="a-elim a-export m-export d-export">Sex</th>
                                <th class="a-elim a-export m-export d-export">Colour</th>
                                <th class="a-elim a-export m-export d-export">Breed</th>
                                <th class="a-elim a-export m-export d-export">YOB</th>
                                <th class="hide d-export">HorseOrigin</th>
                                <th class="hide d-export">Horse Chip</th>
                                <th class="hide d-export">Rider Weight</th>
                                <th class="a-elim a-export m-export d-export">Owner</th>
                                <th class="a-elim a-export m-export d-export">Trainer</th>
                                <th class="a-elim a-export m-export d-export">Stable</th>
                                <th class="hide m-export d-export">Division</th>
                                <th class="hide m-export d-export">TEAM</th>
                                <th class="hide m-export d-export">Transponsder Code</th>
                                @if(!in_array($key,["re","pdf","pfr"]))
                                    <th class="d-export">QR</th>
                                @else
                                    <th class="a-elim">Remarks</th>
                                @endif
                                <th class="a-elim">Status</th>
                                <th class="hide">Reserved</th>
                                @if(!in_array($key,["re","pdf","pfr"]))
                                    <th width="100" style="text-align:right">ACTIONS</th>
                                @endif 
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($lists as $entry)
                                <tr>
                                    <td class="text-center"></td>
                                    
                                    <td class="text-center">
                                    {{$entry->code ?? 'N/A'}}
                                    </td>
                                    <td class="text-center">
                                    {{$entry->userid ?? 'N/A'}}
                                    </td>
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
                                    <td class="text-center">
                                        {{$entry->reserved ?? 'N/A'}}
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
                    @if($key == "final")
                    <div class="d-grid gap-2 col mt-2">
                        @if(count($actions)>0)
                            <div class="row">
                                <div class="col d-flex justify-content-end align-items-center gap-1">
                                    @foreach($actions as $key => $value)
                                        @if($key=="reserved")
                                            <input type="checkbox" class="{{$value['cname']}}" id={{$key}}-action>
                                            <label for={{$key}}-action>{{$value['lbl']}}</label>
                                        @else
                                        <button class="{{$value['cname']}}" type="button" id={{$key}}-action>{{$value['lbl']}}</button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @foreach($items as $item)
                            <div class="row gx-1">
                                @for($i = 0; $i < count($item["flds"]); $i++)
                                <div class={{$item["cnames"][$i]}}>
                                    <div class="form-floating mb-3">
                                        @if($item['flds'][$i] == "startno")
                                            <select class="form-control" id={{$item["flds"][$i]}}></select>
                                            <input type="text" class="form-control uhide" disabled id={{$item["flds"][$i]}}>
                                            <label for={{$item["flds"][$i]}}>{{$item["lbls"][$i]}}</label>
                                        @else
                                            <input type="text" class="form-control" disabled id={{$item["flds"][$i]}}>
                                            <label for={{$item["flds"][$i]}}>{{$item["lbls"][$i]}}</label>
                                        @endif
                                    </div>
                                </div>
                                @endfor
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
            <div class="row mb-2">
                <div class="col text-center fs-3"><h1>Overall Entries: {{$total}}</h1></div>
            </div>
        @endif
    </div>
        <script type="text/javascript">
            $(document).ready(function(e) {
                let selectedRows = [];
                let mutiselectmode = false;
                let currentCode = -1;
                function getNos() {
                    let urlParams = new URLSearchParams(window.location.search);
                    if(urlParams.has('SearchEventID')){
                        $.ajax({
                            url:`/api/getnos?eventid=${$('#eventid').val()}`,
                            method:"GET",
                            success:function(data){
                                if(data.startnos !== undefined){
                                    toastr['success']('Retrieved Start nos.');
                                    $('#startno').empty();
                                    $('#startno').append('<option disabled selected value="-1">Select</option>');
                                    data.startnos.forEach(element => {
                                        $('#startno').append(`<option value=${element}>${element}</option>`);
                                    });
                                }
                            },
                            error:function(error){
                            }
                        });
                    }
                }

                function clear() {
                    $("select#startno").val("");
                    $("#pStartCode").val("");
                    $("#pRiderName").val("");
                    $("#pHorseName").val("");
                    $("#pOwnerName").val("");
                    $("#pTrainerName").val("");
                    $("#pStableName").val("");
                    $("#entryCode").val("");
                    $("#reserved-action").prop('checked',false);
                }

                function prepareRequestv1(params) {
                    paramslist = [`eventid=${$('#eventid').val()}`,`reserved=${$("#reserved-action").is(':checked')}`]
                    params.forEach(element => {
                        switch (element) {
                            case "startno":
                                paramslist.push(`${element}=${$(`select#${element}`).val()}`)
                                break
                            default:
                                paramslist.push(`${element}=${$(`#${element}`).val()}`)
                                break;
                        }
                    });

                    return paramslist.join('&');
                }

                function resetSelection() {
                    selectedRows.forEach(element =>{
                        final.$(`#${element}`).toggleClass('selected');
                    });
                    selectedRows = [];
                }

                function saveStartno(params,fill=false) {
                    $.ajax({
                        url:`/api/assignno?eventid=${params['eventid']}&entryCode=${params['entryCode']}&startno=${params['startno']}`,
                        method:"GET",
                        success:function(data){
                            getNos();
                            return data.success;
                        },
                        error:function(error){
                            return false;
                        }
                    });
                }
                getNos();
                
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
                    ],
                    rowId:[1]
                });
                let fi= 1;
                final.cells(null, 0, {}).every(function (cell) {
                    this.data(fi++);
                });
                $('#final tbody').on('click','tr',function(){
                    let finaldata = final.row(this).data();
                    const idx = final.row(this).id();
                    final.$(`#${idx}`).toggleClass('selected');
                    if(finaldata[3] !== "N/A"){
                        $('#assign-no-action').addClass('shide');
                        $('select#startno').addClass('uhide');
                        $('input#startno').removeClass('uhide');
                    }else{
                        $('#assign-no-action').removeClass('shide');
                        $('select#startno').removeClass('uhide');
                        $('input#startno').addClass('uhide');
                    }
                    if(finaldata[30]=="1" || finaldata==1){
                        $("#reserved-action").prop('checked',true);
                    }else{
                        $("#reserved-action").prop('checked',false);
                    }
                    if($.inArray(idx,selectedRows)==-1){
                        selectedRows.push(idx);
                    }else{
                        if(idx == selectedRows[selectedRows.length-1]){
                            selectedRows.pop();
                            selectedRows.pop();
                        }
                    }
                    if(mutiselectmode){

                    }else{
                        if(selectedRows.length>1){
                            final.$(`#${selectedRows[0]}`).toggleClass('selected');
                            selectedRows = selectedRows.splice(1,1);
                        }
                        if(finaldata[3]!=="N/A"){
                            $("input#startno").val(finaldata[3]);
                        }
                        $("#pStartCode").val(finaldata[4]);
                        $("#pRiderName").val(finaldata[7]);
                        $("#pHorseName").val(finaldata[12]);
                        $("#pOwnerName").val(finaldata[22]);
                        $("#pTrainerName").val(finaldata[23]);
                        $("#pStableName").val(finaldata[24]);
                        $("#entryCode").val(finaldata[1]);
                        if(selectedRows.length<=0){
                            clear();
                        }
                    }

                    
                    // console.log(selectedRows);
                    // if(selectedRows.length>0){
                    //     fdata = final.row(`#${selectedRows[0]}`).data();
                    //     if(fdata[4] == "N/A"){
                    //         console.log('aa');
                            
                    //         $(`#startno_${selectedRows[0]}`).on('change',function(e){
                    //             const params = {
                    //                 'entryCode':selectedRows[0],
                    //                 'eventid':$("#eventid").val(),
                    //                 'startno':e.target.value
                    //             }
                    //             saveStartno(params);
                    //             console.log(selectedRows);
                    //             $.ajax({
                    //                 url:`/api/getentry?entryCode=${selectedRows[0]}`,
                    //                 method:"GET",
                    //                 success: function (data) {
                    //                     fdata = final.row(`#${selectedRows[0]}`).data();
                    //                     fdata[4] = data.entry.startno ?? 'N/A'
                    //                     final.row(`#${selectedRows[0]}`).data(fdata).draw(false);
                    //                     if(fdata[4]=="N/A"){
                    //                         final.$(`#startno_${selectedRows[0]}`).toggleClass('shide');
                    //                     }else{
                    //                         final.$(`#unassignno_${selectedRows[0]}`).toggleClass('uhide');
                    //                     }
                    //                     console.log('asdd');
                    //                 },
                    //                 error:function(error){

                    //                 }
                                
                    //             })
                    //         });
                    //     }else{
    
                    //     }
                    // }
                });

                $('#assign-no-action').on('click', function(e){
                    const params = ['startno','entryCode'];
                    if($('select#startno').val()!= "" && $('#entryCode').val()!= "" && $('#eventid').val() != ""){
                        $.ajax({
                            url:`/api/assignno?${prepareRequestv1(params)}`,
                            method:'GET',
                            success:function(data){
                                toastr.success("Start number assigned.");
                                fdata = final.row(`#${selectedRows[0]}`).data();
                                fdata[3] = $(`#startno`).val() ?? 'N/A'
                                final.row(`#${selectedRows[0]}`).data(fdata).draw(false);
                            
                                $('#assign-no-action').addClass('shide');
                                $('select#startno').addClass('uhide');
                                $('input#startno').removeClass('uhide');
                                
                                getNos();
                                clear();
                                resetSelection();
                            },
                            error:function(error){
                            }
                        });
                    }
                    
                });

                $('#reserved-action').on('change',function(){
                    $(this).val(!this.checked);
                    const params = ['startno','entryCode'];
                    if($('select#startno').val()!= "" && $('#entryCode').val()!= "" && $('#eventid').val() != ""){
                        $.ajax({
                            url:`/api/reserve?${prepareRequestv1(params)}`,
                            method:'GET',
                            success:function(data){
                                if($("#reserved-action").is(':checked')){
                                    toastr.success("Start number reserved.");
                                }else{
                                    toastr.success("Start number unreserved.");
                                }
                            },
                            error:function(error){
                            }
                        });
                    }
                });

                $('#unassign-no-action').on('click', function(e){
                    const params = ['entryCode'];
                    if($('#entryCode').val()!= "" && $('#eventid').val() != ""){
                        $.ajax({
                            url:`/api/assignno?startno=-2&${prepareRequestv1(params)}`,
                            method:'GET',
                            success:function(data){
                                toastr.success("Start number unassigned.");
                                fdata = final.row(`#${selectedRows[0]}`).data();
                                fdata[3] = 'N/A'
                                final.row(`#${selectedRows[0]}`).data(fdata).draw(false);
                                $('#assign-no-action').removeClass('shide');
                                $('select#startno').removeClass('uhide');
                                $('input#startno').addClass('uhide');
                                getNos();
                                clear();
                                resetSelection();

                            },
                            error:function(error){

                            }
                        });
                    }
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
                let pfai= 1;
                pfa.cells(null, 0, {}).every(function (cell) {
                    this.data(pfai++);
                });
                let prov = $('#prov').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Provisional List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-export'}},
                        {...excel, filename:`(Provisional List) ${f[eventval]}_media`, className:'mexport',text:'Media Excel',exportOptions:{columns:'.m-export'}},
                        {...excel, filename:`(Provisional List) ${f[eventval]}_timing`, className:'dexport',text:'Timing Excel',exportOptions:{columns:'.d-export'}},
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
                let provi= 1;
                prov.cells(null, 0, {}).every(function (cell) {
                    this.data(provi++);
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
                let royprovi= 1;
                royprov.cells(null, 0, {}).every(function (cell) {
                    this.data(royprovi++);
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
                let pfri= 1;
                pfr.cells(null, 0, {}).every(function (cell) {
                    this.data(pfri++);
                });
                let re = $('#re').DataTable({
                    dom: dom,
                    buttons:[
                        {...excel, filename:`(Not Qualified List) ${f[eventval]}_admin`, className:'aexport',text:'Admin Excel',exportOptions:{columns:'.a-elim'}},
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
                let rei= 1;
                re.cells(null, 0, {}).every(function (cell) {
                    this.data(rei++);
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
                let urlParams = new URLSearchParams(window.location.search);

                    if(urlParams.has('stablename')){
                        if($('#stableid').val().length==0){
                            urlParams.delete('stablename');
                        }else{
                            urlParams.set('stablename',$('#stableid').val());
                        }
                    }else{
                        urlParams.append('stablename',$('#stableid').val());
                    }
                    window.location.search = urlParams;
                
                

            });
            $(document).on('click', '#reject-entry', function(e) {
                e.preventDefault();
                let self = this;
                let href = $(self).attr('href');
                let urlParams = new URLSearchParams(window.location.search);
                if(urlParams.has('stablename')){
                    href = href + "&stablename=" + urlParams.get('stablename');
                }
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
                let href = $(self).attr('href');
                let urlParams = new URLSearchParams(window.location.search);
                if(urlParams.has('stablename')){
                    href = href + "&stablename=" + urlParams.get('stablename');
                }
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
                let href = $(self).attr('href');
                let urlParams = new URLSearchParams(window.location.search);
                if(urlParams.has('stablename')){
                    href = href + "&stablename=" + urlParams.get('stablename');
                }
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
                let href = $(self).attr('href');
                let urlParams = new URLSearchParams(window.location.search);
                if(urlParams.has('stablename')){
                    href = href + "&stablename=" + urlParams.get('stablename');
                }
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
            $(document).on('click', '#unassign-no', function(e) {
                console.log()
            });
        </script>
@endsection