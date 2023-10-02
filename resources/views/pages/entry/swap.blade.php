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
    <div class="content col col-md col-xl col-xxl-10 align-items-center justify-content-center">
        <div class="entries container py-5">
            <div class="row entry">
                <div class="col">
                    <div class="form-group mb-3">
                        <p class="fs-2">Swap Entry</p>
                        <input class="form-control" id="disabledInput" type="text"
                            placeholder="{{ $oldEntry->horsenfid }} | {{ $oldEntry->horsename }} | {{ $oldEntry->horsefeiid }} | {{ $oldEntry->ridernfid }} | {{ $oldEntry->ridername }} | {{ $oldEntry->riderfeiid }}"
                            disabled>
                        <div id="entryHelpBlock" class="form-text my-1">
                            This entry will be swap on the choice at the bottom.
                        </div>
                        <select class="entry-select select-2-basic col-12"></select>
                    </div>
                </div>
            </div>
        </div>
        <div class="entries container">
            <div class="row entry">
                <div class="col">
                    <div class="form-group mb-3">
                        <a id="entry-submit" class="btn btn-main col-12">SUBMIT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            let entries = {!! json_encode($entries) !!};
            console.log(entries);
            let data = $.map(entries, function(value, key) {
                console.log(value)
                return {
                    id: value.code,
                    text: value.horsenfid + ' | ' + value.horsename + ' | ' + value.horsefeiid + ' | ' +
                        value.ridernfid + ' | ' + value.ridername + ' | ' + value.riderfeiid,
                };
            });

            $(".entry-select").empty().select2({
                data: data
            });
        });

        $(document).on('click', '#entry-submit', function(e) {
            e.preventDefault();
            let self = this;
            let href = $(self).attr('href');
            let params = new URLSearchParams(window.location.search);
            let eid = params.get('raceid');
            let uid = params.get('user');

            let entryid1 = params.get('entrycode');
            let entryid2 = $('.entry-select.select-2-basic').val();
            console.log('entryid2', entryid2)
            console.log('href', href)
            let env_url = '{{ env('UAEERF_PROCESS_URL') }}';
            $.ajax({
                type: 'POST',
                headers: {
                    '38948f839e704e8dbd4ea2650378a388': '0b5e7030aa4a4ee3b1ccdd4341ca3867'
                },

                url: `${env_url}/swapentries?params[UserID]=${uid}&params[EventID]=${eid}&params[EntryID]=${entryid1}&params[EntryID2]=${entryid2}`,
                success: function(data, status, xhr) { // success callback function
                    if (data.swapresult == 'true') {
                        toastr['success']('Swapping Entry is currently in progress')
                        let redirect = `{!! url()->previous() !!}`;
                        setTimeout(function() {
                            window.location.href = redirect;
                        }, 2000);
                    } else {
                        toastr['error'](data.msgs[0])
                        let redirect = `{!! url()->previous() !!}`;
                        setTimeout(function() {
                            window.location.href = redirect;
                        }, 2000);


                    }
                    console.log('data', data);
                    console.log('status', status);
                },
                error: function(jqXhr, textStatus, errorMessage) { // error callback
                    console.log('error', errorMessage);
                }
            });
        });
    </script>
@endsection
