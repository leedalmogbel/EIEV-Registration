@extends('layouts.tapp')
@section('content')
    <div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <h1>{{ Str::upper($modelName) }}</h1>
        <table id={{ $modelName }} class="table table-striped table-bordered">
            <thead>
                <tr>
                    <!-- <th width="300">Race</th> -->
                    <th>HORSE</th>
                    <th>RIDER</th>
                    <th>TRAINER</th>
                    <td>STABLE/OWNER</td>
                    <td>USER</td>
                    <th>ENTRYCODE</th>
                    <th>STATUS</th>
                    <th>STARTNO</th>
                    <th width="100" style="text-align:right">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <td class="text-center">
                            <p class="h6">{{ $entry->horsename }}</p>
                            <p class="h6">{{ $entry->horsenfid }} / {{ $entry->horseid }}</p>
                            <p class="h6">{{ $entry->horsefeiid }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->ridername }}</p>
                            <p class="h6">{{ $entry->ridernfid }} / {{ $entry->riderid }}</p>
                            <p class="h6">{{ $entry->riderfeiid }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->trainername }}</p>
                            <p class="h6">{{ $entry->trainernfid }} / {{ $entry->trainerid }}</p>
                            <p class="h6">{{ $entry->trainerfeiid }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->ownername }} / {{ $entry->ownerid }} </p>
                            <p class="h6">{{ $entry->stablename }} / {{ $entry->stableid }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->userid }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->code }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->status }}</p>
                        </td>
                        <td class="text-center">
                            <p class="h6">{{ $entry->startno }}</p>
                        </td>
                        <td class="text-center">
                            <div class="d-block">
                                <a href="#" class="btn btn-success" id="sub-entry"
                                    data-entrycode="{{ $entry->code }}" data-userid="{{ $entry->userid }}">
                                    <i class="fa-solid fa-shuffle"></i>
                                    Substitute
                                </a>

                                <a href="#" class="btn btn-success" id="swab-entry">
                                    <i class="fa-solid fa-rotate"></i>
                                    Swap
                                </a>
                                {{-- <a id="select-data"
                                    href={{ $profile->stableid == 'E0000014'
                                        ? 'AdminUserID|' . $profile->userid . '|' . $profile->userid
                                        : 'StableID|' . $profile->stableid . '|' . $profile->userid }}
                                    class="btn btn-main">SELECT</a> --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="sub-entries container py-5 d-none">
            <div class="row entry">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="">Horse</label>
                        <select class="horse-select select-2-basic col-12"></select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="">Rider</label>
                        <select class="rider-select select-2-basic col-12"></select>
                    </div>
                </div>

            </div>
        </div>
        <div class="sub-entries1 container">
            <div class="row entry">
                <div class="col">
                    <div class="form-group mb-3">
                        <a id="check-combo" class="btn btn-main col-12" href="submitentry/add">CHECK ELIGIBILITY</a>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group mb-3">
                        <a id="entry-submit" class="btn btn-main col-12" href="submitentry/add">SUBMIT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            let uid = 0;
            let entryCode = '';
            let userID = '';
            $('.entry-select.select-2-basic').select2({
                ajax: {
                    url: '/api/ajax/searchentry',
                    // url: 'https://registration.eiev-app.ae/api/ajax/searchevent',
                    dataType: 'json',
                    type: 'GET',
                    processResults: function(data) {
                        return {
                            "results": $.map(data.events.data, function(obj) {
                                obj.id = obj.raceid || obj.raceid;
                                obj.text = obj.text ||
                                    `${obj.racename}" - ${obj.racelocation} | ${obj.racefromdate}`;

                                return obj;
                            })
                        }
                    }
                }
            });
            $('#changeentry').DataTable({});

            $(document).on('click', '#check-combo', function(e) {
                e.preventDefault();
                let params = new URLSearchParams(window.location.search);
                const hid = $('.horse-select.select-2-basic').val();
                const rid = $('.rider-select.select-2-basic').val();
                const eid = params.get('event');
                console.log('eid', params.get('event'))
                console.log('hid', hid)
                console.log('rid', rid)

                Promise.all([
                    $.ajax({
                        type: 'GET',
                        url: `https://devregistration.eiev-app.ae/api/ridercheck?RiderID=${rid}&EventID=${eid}`,
                        success: function(data, status, xhr) { // success callback function

                        },
                        error: function(jqXhr, textStatus, errorMessage) { // error callback
                            console.log('error', errorMessage);
                        }
                    }),
                    $.ajax({
                        type: 'GET',
                        url: `https://devregistration.eiev-app.ae/api/horsecheck?RiderID=${rid}&EventID=${eid}&HorseID=${hid}`,
                        success: function(data, status, xhr) { // success callback function

                        },
                        error: function(jqXhr, textStatus, errorMessage) { // error callback
                            console.log('error', errorMessage);
                        }
                    }),
                    $.ajax({
                        type: 'GET',
                        // url: `https://devregistration.eiev-app.ae/api/entrycheck?RiderID=${rid}&HorseID=${hid}`,
                        url: `/api/entrycheck?RiderID=${rid}&HorseID=${hid}&eventcode=${eid}`,
                        success: function(data, status, xhr) { // success callback function

                        },
                        error: function(jqXhr, textStatus, errorMessage) { // error callback
                            console.log('error', errorMessage);
                        }
                    }),
                ]).then((response) => {
                    if (response[0].ridereligibility == 'YES') {
                        toastr['success']('Rider is eligible');
                    } else {
                        toastr['error'](response[0].ridereligibility);
                    }

                    if (response[1].horseeligibility == 'YES') {
                        toastr['success']('Horse is eligible');
                    } else {
                        toastr['error'](response[1].horseeligibility);
                    }

                    if (response[2].entryexist) {
                        toastr['error'](response[2].msg);
                    } else {
                        toastr['success']('entry is free');
                    }
                    console.log('response', response)
                });
                // $.ajax(`http://127.0.0.1:8000/api/ridercheck?RiderID=${rid}&EventID=${eid}`,

            });

            $(document).on('click', '#entry-submit', function(e) {

                e.preventDefault();
                let self = this;
                let href = $(self).attr('href');
                let params = new URLSearchParams(window.location.search);
                const eid = params.get('event');
                const bcode = params.get('code');
                // const eid = $('.event-select.select-2-basic').val();
                const hid = $('.horse-select.select-2-basic').val();
                const rid = $('.rider-select.select-2-basic').val();
                href =
                    `https://devregistration.eiev-app.ae/api/processentry?eventcode=${eid}&horseID=${hid}&riderID=${rid}&userID=${userID}&entrycode=${entryCode}&bcode=${bcode}`;
                window.location.href = href;
            });

            $(document).on('click', '#sub-entry', function(e) {
                e.preventDefault();
                let self = this;
                const href = $(self).attr('href');
                console.log('href', href);
                const ddata = href.split("|");
                const dkey = ddata[0];
                const dval = ddata[1];
                console.log('ddata', ddata[1])
                uid = ddata[2];
                entryCode = this.dataset.entrycode;
                userID = this.dataset.userid;
                console.log('entryCode', entryCode)
                console.log('uid', userID)
                $('.sub-entries').removeClass('d-none');
                $('.horse-select.select-2-basic').val(null).trigger('change');
                $('.rider-select.select-2-basic').val(null).trigger('change');
                $('.horse-select.select-2-basic').select2({
                    ajax: {
                        url: 'https://registration.eiev-app.ae/api/ajax/searchhorse',
                        dataType: 'json',
                        type: 'GET',
                        data: function(params) {
                            let query = {
                                SearchName: params.term,
                                dkey: dval
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                "results": $.map(data.horses.data, function(obj) {
                                    obj.id = obj.horseid || obj.horseid;
                                    obj.text = obj.text ||
                                        `${obj.horseid} / ${obj.name} / ${obj.nfregistration} / ${obj.gender} / ${obj.color}`;

                                    return obj;
                                })
                            }
                        }
                    }
                });
                $('.rider-select.select-2-basic').select2({
                    ajax: {
                        url: 'https://registration.eiev-app.ae/api/ajax/searchrider',
                        dataType: 'json',
                        type: 'GET',
                        data: function(params) {
                            let query = {
                                SearchFullName: params.term,
                                dkey: dval
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                "results": $.map(data.riders.data, function(obj) {
                                    obj.id = obj.riderid || obj.riderid;
                                    obj.text = obj.text ||
                                        `${obj.firstx0020name} ${obj.familyx0020name} (${obj.stable}) ${obj.nfx0020license} / ${obj.feix0020reg} / ${obj.countryshort}`; // replace pk with your identifier
                                    return obj;
                                })
                            }
                        }
                    }
                });

            });

        });
    </script>
@endsection
