@extends('layouts.tapp')
@section('content')
<div class="d-grid gap-2 mt-2">
    
    <div class="input-group input-group-lg input-toggle" id="input-search">
        <input type="text" id="search" class="form-control text-center" autofocus>
        
    </div>
    @if(count($entries)>0)
        <h1>Entries Total: {{ count($entries)}}</h1>
        <div class="table-responsive mt-2">
            <table id="my-entries" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="a-elim a-export m-export d-export">serial</th>
                        <th>pEntryCode</th>
                        <th>UserId</th>
                        <th>pEvtCateg</th>
                        <th>pIdCode</th>
                        <th class="a-elim a-export m-export d-export">pStartNo</th>
                        <th class="a-elim a-export m-export d-export">pStartCode</th>
                        <th class="hide m-export d-export">pRiderName</th>
                        <th class="hide m-export d-export">pRiderFname</th>
                        <th class="hide m-export d-export">pRiderLname</th>
                        <th class="hide m-export d-export">pRiderLicenseFei</th>
                        <th class="a-elim a-export">pRiderLicenseEef</th>
                        <th class="a-elim a-export m-export d-export">pRiderNationality</th>
                        <th class="a-elim a-export m-export d-export">pHorseName</th>
                        <th class="a-elim a-export m-export d-export">pHorseYear</th>
                        <th class="hide d-export">pHorseGender</th>
                        <th class="a-elim a-export m-export d-export">pHorseColor</th>
                        <th class="a-elim a-export m-export d-export">pHorseBreed</th>
                        <th class="a-elim a-export m-export d-export">pHorseLicenseFei</th>
                        <th class="a-elim a-export m-export d-export">pHorseLicenseEef</th>
                        <th class="a-elim a-export m-export d-export">pHorseChip</th>
                        <th class="a-elim a-export m-export d-export">pOwnerName</th>
                        <th class="a-elim a-export m-export d-export">pTrainerName</th>
                        <th class="hide d-export">pStableName</th>
                        <th class="hide d-export">pContactPerson</th>
                        <th class="hide d-export">pContactNumber</th>
                        <th class="a-elim a-export m-export d-export">pRiderImage</th>
                        <th class="a-elim a-export m-export d-export">pExecutedBy</th>
                        <th class="a-elim a-export m-export d-export">pHorseOrigin</th>
                        <th class="hide m-export d-export">pRiderGender</th>
                        <th class="hide m-export d-export">pBarcodeValue</th>
                        <th class="hide m-export d-export">status</th>                        
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($entries as $entry)
                        <tr>
                            <td class="text-center"></td>
                            <td class="text-center">
                            {{$entry->code ?? 'N/A'}}
                            </td>
                            <td class="text-center">
                            {{$entry->userid ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->racecode ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->eventcode ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->startno ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->racestartcode ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->ridername ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->rfname ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->rlname ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->riderfeiid ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->ridernfid ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->rcountry ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->horsename ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{date('Y',strtotime($entry->yob)) ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{ $entry->hgender ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{ $entry->color ?? 'N/A'}}
                            </td> 
                            <td class="t-data text-center">
                            {{$entry->breed ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->horsefeiid ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                                {{$entry->horsenfid ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                                {{$entry->microchip ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->ownername ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->trainername ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                            {{$entry->stablename ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                                {{$profile->fname. ' '. $profile->lname}}
                            </td>
                            <td class="t-data text-center">
                                {{$profile->mobileno}}
                            </td>
                            <td class="t-data text-center">
                            </td>

                            <td class="t-data text-center">
                            EIEV Admin R10
                            </td>
                            <td class="t-data text-center">
                                {{$entry->horigin ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                                {{$entry->rgender ?? 'N/A'}}
                            </td>
                            <td class="t-data text-center">
                                {{$entry->qrval ?? 'N/A'}}
                            </td>
                            <td class="text-center">
                            {{$entry->status ?? 'N/A'}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if($profile)
        <div class="row">
            <div class="col row ">
                <div class="d-flex flex-column gap-1 col row">
                    <div class="col text-center">{{$profile->fname . " ". $profile->lname}}</div>
                    <div class="col text-center">{{$profile->email}}</div>
                    <div class="col text-center">{{$profile->userid}}</div>
                    <div class="col text-center">{{$profile->stableid}}</div>
                    <div class="print-it col text-center">{{QrCode::style($request->style??'square')->encoding('UTF-8')->size($request->size ?? 200)->generate($profile->uniqueid)}}</div>
                    <div class="col mt-3 text-center"style="flex-grow: 10;">
                        <div class="fs-4">Rider Preride Status</div>
                        <div id="rider-status" class="mt-3 fs-3"></div>
                    </div>
                </div>
                <div class="d-grid gap-2 col-9">
                    @if(count($actions)>0)
                        <div class="row">
                            <div class="col d-flex justify-content-end gap-1">
                                @foreach($actions as $key => $value)
                                    <button class="{{$value['cname']}}" type="button" id={{$key}}-action>{{$value['lbl']}}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @foreach($inputs as $item)
                        <div class="row gx-1">
                            @for($i = 0; $i < count($item["flds"]); $i++)
                            <div class={{$item["cnames"][$i]}}>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id={{$item["flds"][$i]}}>
                                    <label for={{$item["flds"][$i]}}>{{$item["lbls"][$i]}}</label>
                                </div>
                            </div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
<style>
    .input-toggle{
        opacity:0;
        margin-left:-200%;
        margin-top:-5rem;
        transition: 1s;
        z-index:-9999;
    }
</style>
<script>
    $(document).ready(function(e) {
        let urlParams = new URLSearchParams(window.location.search);
        const pRiderImage=null;
        const pExecutedBy="EIEV Admin R10";
        let pBarcodeValue="";
        let pStartNo1=-1;
        let pStartNo2=-1;
        let pRaceId = -1;
        let startnoToggle = -1;
        let swapEnabled = false;
        let selectedRows = [];
        let entriestbl = $('#my-entries').DataTable({
            rowId: [1]
        });
        let fi= 1;
        const baseurl = '{{ env("R10_BASE_URL") }}'
        console.log(baseurl)
        entriestbl.cells(null, 0, {}).every(function (cell) {
            this.data(fi++);
        });
        function toggleStartNo() { 
            startnoToggle++;
            return  startnoToggle % 2 === 0;
        }
        $('#my-entries tbody').on( 'click', 'tr', function () {
            const tbldata = entriestbl.row(this).data();
            const idx=entriestbl.row(this).id();
            entriestbl.$(`#${idx}`).toggleClass('selected');
            
            $.ajax({
                url:`${baseurl}/api/execute?action=FetchStartNoDetails&pRaceId=${tbldata[4]}&pStartNo=${tbldata[5]}&include=pRiderLocation`,
                method:'GET',
                success: function (data) {
                    if(data.fetchstartnodetailsresult === undefined) {toastr['error'](`Something went wrong.`);return}
                    if(data.fetchstartnodetailsresult.result == "true"){
                        toastr['success'](`Start Number Details request for #${$(`#pStartNo`).val()} sent successfully.`);
                        toastr['info'](`Start Number Details for #${$(`#pStartNo`).val()} is ${data.fetchstartnodetailsresult.status}. Remarks: ${data.fetchstartnodetailsresult.remarks}`);
                        $("#rider-status").text(data.fetchstartnodetailsresult.priderlocation);
                    }else{
                        toastr['error'](`Something went wrong. Msg: ${data.fetchstartnodetailsresult.errormsg}`);
                    }

                },
                error: function (error) {
                    toastr['error'](`Something went wrong.`);
                }
            });
            if($.inArray(idx,selectedRows) == -1){
                if(swapEnabled){
                    if(toggleStartNo() == true){
                        pStartNo1 = tbldata[5]
                    }else{
                        pStartNo2 = tbldata[5]
                    }
                    pRaceId = tbldata[4]
                }
              
                selectedRows.push(idx);
            }else{
                if(idx == selectedRows[selectedRows.length-1]){
                    selectedRows.pop();
                    selectedRows.pop();
                    if(swapEnabled){
                        if(pStartNo1 == tbldata[5]){
                            pStartNo1 = -1;
                        }
                        if(pStartNo2 == tbldata[5]){
                            pStartNo2 = -1;
                        }
                    }
                    
                }
            }
            if(swapEnabled){
                if(selectedRows.length > 2){
                    entriestbl.$(`#${selectedRows[0]}`).toggleClass('selected');
                    selectedRows = selectedRows.splice(1,selectedRows.length-1);
                }
            }else{
                if(selectedRows.length > 1){
                    entriestbl.$(`#${selectedRows[0]}`).toggleClass('selected');
                    selectedRows = selectedRows.splice(1,1);
                }

                $("#pStartNo").val(tbldata[5]);
                $("#pStartCode").val(tbldata[6]);
                $("#pOwnerName").val(tbldata[21]);
                $("#pTrainerName").val(tbldata[22]);
                $("#pStableName").val(tbldata[23]);
                $("#pRiderName").val(tbldata[7]);
                $("#pRiderFname").val(tbldata[8]);
                $("#pRiderLname").val(tbldata[9]);
                $("#pRiderLicenseFei").val(tbldata[10]);
                $("#pRiderLicenseEef").val(tbldata[11]);
                $("#pRiderNationality").val(tbldata[12]);
                $("#pRiderGender").val(tbldata[29]);
                $("#pHorseName").val(tbldata[13]);
                $("#pHorseLicenseFei").val(tbldata[18]);
                $("#pHorseLicenseEef").val(tbldata[19]);
                $("#pHorseOrigin").val(tbldata[28]);
                $("#pHorseYear").val(tbldata[14]);
                $("#pHorseGender").val(tbldata[15]);
                $("#pHorseColor").val(tbldata[16]);
                $("#pHorseBreed").val(tbldata[17]);
                $("#pHorseChip").val(tbldata[20]);
                $("#pContactPerson").val(tbldata[24]);
                $("#pContactNumber").val(tbldata[25]);
                $("#pEvtCateg").val(tbldata[3]);
                $("#pIdCode").val(tbldata[4]);
                pBarcodeValue=tbldata[30];
                if(selectedRows.length<=0){
                    clear();
                }
            }
        } );

        function clear() {
            $("#pStartNo").val("");
            $("#pStartCode").val("");
            $("#pOwnerName").val("");
            $("#pTrainerName").val("");
            $("#pStableName").val("");
            $("#pRiderName").val("");
            $("#pRiderFname").val("");
            $("#pRiderLname").val("");
            $("#pRiderLicenseFei").val("");
            $("#pRiderLicenseEef").val("");
            $("#pRiderNationality").val("");
            $("#pRiderGender").val("");
            $("#pHorseName").val("");
            $("#pHorseLicenseFei").val("");
            $("#pHorseLicenseEef").val("");
            $("#pHorseOrigin").val("");
            $("#pHorseYear").val("");
            $("#pHorseGender").val("");
            $("#pHorseColor").val("");
            $("#pHorseBreed").val("");
            $("#pHorseChip").val("");
            $("#pContactPerson").val("");
            $("#pContactNumber").val("");
            $("#pEvtCateg").val("");
            $("#pIdCode").val("");
            pBarcodeValue = "";
        }


        let keyLog = {}
        const handleKeyboard = ({ type, key, repeat, metaKey }) => {
            if (repeat) return
            if (type === 'keydown') {
                keyLog[key] = true
                if (key === '`'){
                    $("#input-search").toggleClass('input-toggle');
                    searchfield.focus();
                }
                if (key.enter){
                    keyLog = {};
                }
            }
        }

        document.addEventListener('keydown', handleKeyboard)
        let searchfield = $('#search').on('blur',function(e){
            if(urlParams.has('code')) return
            setTimeout(function () {
                searchfield.focus();
            },10);
        });
        $('#search').on('keypress', function (event) {
            var regex = new RegExp("[^`]");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
        $('#search').keyup($.debounce( 500, function(){
            if( $("#search").val() !== ""){
                setTimeout(function () {
                    
                    let code = $('#search').val();
                    if(urlParams.has('code')){
                        urlParams.set('code',code);
                    }else{
                        urlParams.append('code',code);
                    }
                    window.location.search=urlParams;
                    $("#search").val('');
                    searchfield.focus();
                },500);
            }
        }));

        function prepareRequestv1(params) {
            paramslist = [`pRiderImage=${pRiderImage}`]
            params.forEach(element => {
                switch (element) {
                    case "pIdCode":
                        paramslist.push(`pRaceId=${$(`#${element}`).val()}`)
                        break;
                    case "pStartNo1":
                        paramslist.push(`pStartNo1=${pStartNo1}`)
                        break;
                    case "pStartNo2":
                        paramslist.push(`pStartNo2=${pStartNo2}}`)
                        break;
                    case "pBarcodeValue":
                        paramslist.push(`pBarcodeValue=${pBarcodeValue}`)
                    break;    
                    case "pExecutedBy":
                        paramslist.push(`pExecutedBy=${pExecutedBy}`)
                    break;
                    default:
                        paramslist.push(`${element}=${$(`#${element}`).val()}`)
                        break;
                }
            });
            return paramslist.join('&');
        }

        function prepareRequestv2(params) {
            paramslist = [`pRiderImage=${pRiderImage}`]
            params.forEach(element => {
                switch (element) {
                    case "pExecutedBy":
                        paramslist.push(`pExecutedBy=${pExecutedBy}`)
                    break;
                    case "pBarcodeValue":
                        paramslist.push(`pBarcodeValue=${pBarcodeValue}`)
                    break;
                    default:
                        paramslist.push(`${element}=${$(`#${element}`).val()}`)
                        break;
                }
            });
            return paramslist.join('&');
        }

        function resetSelection() {
            selectedRows.forEach(element => {
                    entriestbl.$(`#${element}`).toggleClass('selected');
                });
            pRaceId = -1;
            pStartNo1 = -1;
            pStartNo2 = -1;
            selectedRows = [];
        }

        function toggleSwap() {
            swapEnabled =!swapEnabled;
            $('#swap-action').toggleClass("d-none");
            $('#swap-toggle-action').text(swapEnabled?"Cancel Swap Mode":"Enable Swap Mode");
            clear();
            resetSelection()
        }
        $('#swap-toggle-action').on('click',function(e){
            toggleSwap();
        });

        $('#swap-action').on('click',function(e){
            const params = [
                'pIdCode',
                'pStartNo1',
                'pStartNo2',
                'pExecutedBy',
            ]
           
            $.ajax({
                url:`${baseurl}/api/execute?action=SwapRiders&${prepareRequestv1(params)}`,
                type:"GET",
                success: function (data) {
                    if(data.swapridersresult === undefined) {toastr['error'](`Something went wrong.`);return}
                    if(data.swapridersresult.result == "true"){
                        toastr['success'](`Update entry request for #${$(`#pStartNo`).val()} sent successfully.`);
                        toastr['info'](`Update entry status for #${$(`#pStartNo`).val()} is ${data.swapridersresult.status}. Remarks: ${data.swapridersresult.remarks}`);
                        
                    }else{
                        toastr['error'](`Something went wrong. Msg: ${data.swapridersresult.errormsg}`);
                    }

                },
                error: function (error) {
                    console.log(error)
                    toastr['error'](`Something went wrong.`);
                }
            })
        })

        $('#update-action').on('click',function(e){
            prevent = true
            const params = [
            'pEvtCateg',
            'pIdCode',
            'pStartNo',
            'pStartCode',
            'pRiderName',
            'pRiderFname',
            'pRiderLname',
            'pRiderLicenseFei',
            'pRiderLicenseEef',
            'pRiderNationality',
            'pHorseName',
            'pHorseYear',
            'pHorseGender',
            'pHorseColor',
            'pHorseBreed',
            'pHorseLicenseFei',
            'pHorseLicenseEef',
            'pHorseChip',
            'pOwnerName',
            'pTrainerName',
            'pStableName',
            'pContactPerson',
            'pContactNumber',
            'pRiderImage',
            'pExecutedBy',
            'pHorseOrigin',
            'pRiderGender',
            'pBarcodeValue',
                ]
            paramcheck = []
            params.forEach(element => {
                if(element != "pExecutedBy" && element != "pHorseLicenseFei" && element != "pRiderLicenseFei"){
                    if(element == "pBarcodeValue"){
                        paramcheck.push(pBarcodeValue!== "")
                        console.log(element,'=>',pBarcodeValue)
                    }else{
                        console.log(element,'=>',$(`#${element}`).val())
                        paramcheck.push($(`#${element}`).val() !== "")
                    }
                }
                
            });
            if($.inArray(false,paramcheck)>=0){
                toastr['error'](`Something went wrong. All fields are required.`);
                return
            }
            $.ajax({
                url:`${baseurl}/api/execute?action=UpdateEntriesV2&${prepareRequestv2(params)}`,
                type:"GET",
                success: function (data) {
                    if(data.updateentriesv2result === undefined) {toastr['error'](`Something went wrong.`);return}
                    if(data.updateentriesv2result.result == "true"){
                        toastr['success'](`Update entry request for #${$(`#pStartNo`).val()} sent successfully.`);
                        toastr['info'](`Update entry status for #${$(`#pStartNo`).val()} is ${data.updateentriesv2result.status}. Remarks: ${data.updateentriesv2result.remarks}`);
                    }else{
                        toastr['error'](`Something went wrong. Msg: ${data.updateentriesv2result.errormsg}`);
                    }
                },
                error: function (error) {
                    console.log(error);
                    toastr['error'](`Something went wrong.`);
                }
            })
        });

        $('#add-action').on('click',function(e){
            const params = [
            'pEvtCateg',
            'pIdCode',
            'pStartNo',
            'pStartCode',
            'pRiderName',
            'pRiderFname',
            'pRiderLname',
            'pRiderLicenseFei',
            'pRiderLicenseEef',
            'pRiderNationality',
            'pHorseName',
            'pHorseYear',
            'pHorseGender',
            'pHorseColor',
            'pHorseBreed',
            'pHorseLicenseFei',
            'pHorseLicenseEef',
            'pHorseChip',
            'pOwnerName',
            'pTrainerName',
            'pStableName',
            'pContactPerson',
            'pContactNumber',
            'pRiderImage',
            'pExecutedBy',
            'pHorseOrigin',
            'pRiderGender',
            'pBarcodeValue',
                ]
           
            $.ajax({
                url:`${baseurl}/api/execute?action=InsertEntriesV2&${prepareRequestv2(params)}`,
                type:"GET",
                success: function (data) {
                    if(data.insertentriesv2result === undefined) {toastr['error'](`Something went wrong.`);return}
                    if(data.insertentriesv2result.result == "true"){
                        toastr['success'](`Added entry for #${$(`#pStartNo`).val()} successfully.`);
                        // toastr['info'](`Delete Rider status for #${$(`#pStartNo`).val()} is ${data.insertentriesv2result.status}. Remarks: ${data.insertentriesv2result.remarks}`);
                    }else{
                        toastr['error'](`Something went wrong. Msg: ${data.insertentriesv2result.errormsg}`);
                    }
                },
                error: function (error) {
                    toastr['error'](`Something went wrong.`);
                }
            })
        });

        $('#delete-action').on('click',function(e){
            const params = [
                'pIdCode',
                'pStartNo',
                'pExecutedBy',
            ]
           
            $.ajax({
                url:`${baseurl}/api/execute?action=DeleteRider&${prepareRequestv1(params)}`,
                type:"GET",
                success: function (data) {
                    if(data.deleteriderresult === undefined) {toastr['error'](`Something went wrong.`);return}
                    if(data.deleteriderresult.result == "true"){
                        toastr['success'](`Delete Rider request for #${$(`#pStartNo`).val()} sent successfully.`);
                        toastr['info'](`Delete Rider status for #${$(`#pStartNo`).val()} is ${data.deleteriderresult.status}. Remarks: ${data.deleteriderresult.remarks}`);
                    }else{
                        toastr['error'](`Something went wrong. Msg: ${data.deleteriderresult.errormsg}`);
                    }
                },
                error: function (error) {
                    toastr['error'](`Something went wrong.`);
                }
            })
        });
    });
</script>
@endsection