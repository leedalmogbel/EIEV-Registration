@extends('partials.frame')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-container--default .select2-results>.select2-results__options {
            max-height: 700px;
        }

        .select2 span,
        .select2-results li {
            color: #444;
            font-weight: 600;
            font-size: 16px;
        }
    </style>
    <div class="col">
        <div class="entries container py-5">
            <p class="fs-3">Old Entry</p>
            <div class="row entry">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="">Horse</label>
                        <input class="form-control" id="disabledInput" type="text"
                            placeholder="{{ $oldEntry->horsenfid }} | {{ $oldEntry->horsename }} | {{ $oldEntry->horsefeiid }}"
                            disabled>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="">Rider</label>
                        <input class="form-control" id="disabledInput" type="text"
                            placeholder="{{ $oldEntry->ridernfid }} | {{ $oldEntry->ridername }} | {{ $oldEntry->riderfeiid }}"
                            disabled>
                    </div>
                </div>
            </div>
            <p class="fs-3">New Entry</p>
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
                        <a id="check-combo" class="btn btn-main col-12" href="#">CHECK ELIGIBILITY</a>
                    </div>
                    <div id="entryHelpBlock" class="form-text my-1 fw-bold fst-italic">
                        check the horse and rider if eligible for the event
                    </div>
                </div>

                <div class="col">
                    <div class="form-group mb-3">
                        <a id="entry-submit" class="btn btn-main col-12" href="#">SUBMIT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            let self = this;
            const href = $(self).attr('href');
            console.log('href', href);
            let ddata = '';
            let stableId = `{{ $profile->stableid }}`;
            if (stableId == 'E0000014') {
                ddata = 'AdminUserID|' + `{{ $profile->userid }}` + '|' + `{{ $profile->userid }}`;
            } else {
                ddata = 'StableID|' + `{{ $profile->userid }}` + '|' + `{{ $profile->userid }}`;
            }

            console.log('this', ddata);
            const dkey = ddata[0];
            const dval = ddata[1];
            let params = new URLSearchParams(window.location.search);
            let eid = params.get('raceid');
            let entryCode = params.get('entrycode');
            let uid = params.get('user');
            console.log('entryCode', entryCode)
            console.log('uid', uid)
            $('.sub-entries').removeClass('d-none');
            $('.horse-select.select-2-basic').val(null).trigger('change');
            $('.rider-select.select-2-basic').val(null).trigger('change');
            $('.horse-select.select-2-basic').select2({
                ajax: {
                    url: 'https://registration.eiev-app.ae/api/ajax/searchhorse',
                    dataType: 'json',
                    type: 'GET',
                    data: function(params) {
                        console.log('dkey', dkey)
                        console.log('dkey', dval)

                        let query = {
                            SearchName: params.term,
                            dkey: dval
                        }
                        console.log('dkey3', dkey)
                        console.log('dkey3', dval)
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

        $(document).on('click', '#check-combo', function(e) {
            e.preventDefault();
            let params = new URLSearchParams(window.location.search);
            const hid = $('.horse-select.select-2-basic').val();
            const rid = $('.rider-select.select-2-basic').val();
            let eid = params.get('raceid');
            eid = eid.replace('0', '');
            console.log('eid', params.get('raceid'));
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

                console.log('response', response)
            });
            // $.ajax(`http://127.0.0.1:8000/api/ridercheck?RiderID=${rid}&EventID=${eid}`,

        });

        $(document).on('click', '#entry-submit', function(e) {
            e.preventDefault();
            let self = this;
            let href = $(self).attr('href');
            let params = new URLSearchParams(window.location.search);
            let eid = params.get('raceid');
            let entryCode = params.get('entrycode');
            let uid = params.get('user');
            const hid = $('.horse-select.select-2-basic').val();
            const rid = $('.rider-select.select-2-basic').val();
            href =
                `https://registration.eiev-app.ae/api/substituteentry?eventcode=${eid}&entrycode=${entryCode}&horseID=${hid}&riderID=${rid}&userID=${uid}`;
            window.location.href = href;
        });
    </script>
@endsection
