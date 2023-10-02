@extends('layouts.tapp')
@section('content')
    <div class="container events my-5">
        <h1>{{ Str::upper('Events') }}</h1>
        <div class="col">
            <div class="form-group mb-3">
                <label for="">Event</label>
                <select class="event-select select-2-basic col-12"></select>
            </div>
        </div>
    </div>
    <div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        {{-- <h1>{{ Str::upper($modelName) }}</h1> --}}
        <h1>{{ Str::upper('Add New Entry') }}</h1>
        <table id={{ $modelName }} class="table table-striped table-bordered">
            <thead>
                <tr>
                    <!-- <th width="300">Race</th> -->
                    <th>ACTIVE</th>
                    <th>EIEVID</th>
                    <th>EMAIL</th>
                    <td>USERID</td>
                    <td>STABLEID</td>
                    <th>FIRSTNAME</th>
                    <th>LASTNAME</th>
                    <th>MOBILENO</th>
                    <th>BDAY</th>
                    <th width="100" style="text-align:right">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($profiles as $profile)
                    <tr>
                        <td class="text-center">
                            {{ $profile->isactive ?? 'N/A' }}
                        </td>
                        <td class="text-center"><strong>{{ $profile->uniqueid }}</strong></td>
                        <td class="text-center"><strong>{{ $profile->email }}</strong></td>
                        <td class="text-center"><strong>{{ $profile->userid }}</strong></td>
                        <td class="text-center"><strong>{{ $profile->stableid }}</strong></td>
                        <td class="text-center">
                            {{ $profile->fname ?? 'UNK' }}
                        </td>
                        <td class="text-center">
                            {{ $profile->lname ?? 'UNK' }}
                        </td>
                        <td class="text-center">
                            {{ $profile->mobileno ?? 'UNK' }}
                        </td>
                        <td class="text-center">
                            {{ date('Y-m-d', strtotime($profile->bday)) ?? 'UNK' }}
                        </td>
                        <td class="text-center">
                            <div>
                                <a id="select-data"
                                    href={{ $profile->stableid == 'E0000014'
                                        ? 'AdminUserID|' . $profile->userid . '|' . $profile->userid
                                        : 'StableID|' . $profile->stableid . '|' . $profile->userid }}
                                    class="btn btn-main my-3"><i class="fa-solid fa-clipboard-check"></i> SELECT</a>

                                <a href="/changeentry?code={{ $profile->uniqueid }}" class="btn btn-main entry-change my-3"
                                    data-uniqueid="{{ $profile->uniqueid }}"><i class="fa-solid fa-right-left"></i>
                                    CHANGE</a>
                                {{-- <a href="/swapentry?code={{ $profile->uniqueid }}" class="btn btn-main entry-swap my-3"
                                    data-uniqueid="{{ $profile->uniqueid }}"><i class="fa-solid fa-repeat"></i> SWAP</a> --}}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="entries container py-5">
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
        <div class="entries container">
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
            let eiev_api_url = '{{ env('EIEV_API_URL') }}';
            let eiev_admin_url = '{{ env('EIEV_ADMIN_URL') }}';
            console.log('eiev_url', eiev_api_url)
            $('.event-select.select-2-basic').select2({
                ajax: {
                    url: `${eiev_api_url}/ajax/searchevent`,
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
            $('#submitentry').DataTable();

            $(document).on('click', '#check-combo', function(e) {
                e.preventDefault();
                const eid = $('.event-select.select-2-basic').val();
                const hid = $('.horse-select.select-2-basic').val();
                const rid = $('.rider-select.select-2-basic').val();
                console.log('eid', eid)
                console.log('hid', hid)
                console.log('rid', rid)

                Promise.all([
                    $.ajax({
                        type: 'GET',
                        url: `${eiev_admin_url}/ridercheck?RiderID=${rid}&EventID=${eid}`,
                        success: function(data, status, xhr) { // success callback function

                        },
                        error: function(jqXhr, textStatus, errorMessage) { // error callback
                            console.log('error', errorMessage);
                        }
                    }),
                    $.ajax({
                        type: 'GET',
                        url: `${eiev_admin_url}/horsecheck?RiderID=${rid}&EventID=${eid}&HorseID=${hid}`,
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
                        toastr['success']('Entry Horse and Rider is eligible');
                    } else {
                        toastr['error'](response[1].horseeligibility);
                    }
                    console.log('response', response)
                });
                // $.ajax(`http://127.0.0.1:8000/api/ridercheck?RiderID=${rid}&EventID=${eid}`,

            });

            $(document).on('click', '#entry-submit', function(e) {

                e.preventDefault();
                let self = this;
                let href = $(self).attr('href');
                const eid = $('.event-select.select-2-basic').val();
                const hid = $('.horse-select.select-2-basic').val();
                const rid = $('.rider-select.select-2-basic').val();
                if (uid > 0) {
                    // href = `https://devregistration.eiev-app.ae/${href}?params[EventID]=${eid}&params[HorseID]=${hid}&params[RiderID]=${rid}&params[UserID]=${uid}`;
                    href =
                        `http://localhost:8000/${href}?params[EventID]=${eid}&params[HorseID]=${hid}&params[RiderID]=${rid}&params[UserID]=${uid}`;
                    console.log(href)
                    window.location.href = href
                }
            });

            $(document).on('change', '.event-select', function(e) {
                // Get the selected option value
                let selectedValue = $(this).val();
                eventCode = selectedValue.replace('000', '');

                // Update href attribute of the link
                // var linkHref = 'https://example.com/' + selectedValue; // Update the URL pattern as needed
                // $('.entry-change').attr('href', linkHref);
                console.log('eventCode', eventCode)
                let newUrl = window.location.origin + window.location.pathname + '?event=' + eventCode;
                history.pushState(null, null, newUrl);
                // $('.entry-change').each(function() {
                //     let uniqueid = $(this).data('uniqueid');
                //     let originalHref = $(this).attr('href');
                //     let newHref = originalHref.split('?')[0]; // Remove previous query parameters
                //     newHref += '?code=' + uniqueid + '&event=' + eventCode;
                //     console.log('newhrf', newHref);
                //     // var extraHref = selectedValue;
                //     // var originalHref = $(this).attr('href');
                //     // var newHref = originalHref + '&extra=' + extraHref; // Append the extra value
                //     $(this).attr('href', newHref);

                //     // $(this).attr('href', newHref);
                // });
            });

            $(document).on('click', '.entry-change', function(e) {
                console.log('tga');
                e.preventDefault();
                let href = $(this).attr('href');
                let searchParams = new URLSearchParams(window.location.search)
                let params = searchParams.get('event');
                console.log('params', params);
                href += '&event=' + params;
                console.log('href', href);
                window.location.href = href;
            });

            // $(document).on('click', '.entry-swap', function(e) {
            //     console.log('tga');
            //     e.preventDefault();
            //     let href = $(this).attr('href');
            //     let searchParams = new URLSearchParams(window.location.search)
            //     let params = searchParams.get('event');
            //     console.log('asdasd' + $(this).val())
            //     console.log('params', params);
            //     href += '&event=' + params;
            //     console.log('href', href);
            //     window.location.href = href;
            // });

            $(document).on('click', '#select-data', function(e) {
                e.preventDefault();
                let self = this;
                const href = $(self).attr('href');
                console.log('href', href);
                const ddata = href.split("|");
                const dkey = ddata[0];
                const dval = ddata[1];
                uid = ddata[2];
                $('.horse-select.select-2-basic').val(null).trigger('change');
                $('.rider-select.select-2-basic').val(null).trigger('change');
                $('.horse-select.select-2-basic').select2({
                    ajax: {
                        url: `${eiev_api_url}/ajax/searchhorse`,
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
                        url: `${eiev_api_url}/ajax/searchrider`,
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
