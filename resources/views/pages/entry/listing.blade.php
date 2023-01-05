<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container--default .select2-results>.select2-results__options {
        max-height: 700px;
    }
</style>
<div class="table-responsive col col-md col-xl col-xxl-10 align-items-center justify-content-center">
    <table class="table table-striped table-bordered">
        <tr>
            <th>Horse</th>
            <th>Rider</th>
            <th>Trainer</th>
            <th>Stable</th>
            <th>Status</th>
            <th>Remarks</th>
            <th width="100" style="text-align:right">ACTIONS</th>
        </tr>
        @foreach ($eef_entries as $entry)
            {{-- @foreach (${Str::plural($modelName)} as $entry) --}}
            <tr>
                <td>
                    <div>{{ $entry->horsename }}</div>
                    <div class="text-secondary">{{ $entry->horsenfid }}</div>
                    <div class="text-secondary">{{ $entry->horsefeiid }}</div>
                </td>
                <td>
                    <div>{{ $entry->ridername }}</div>
                    <div class="text-secondary">{{ $entry->ridernfid }}</div>
                    <div class="text-secondary">{{ $entry->riderfeiid }}</div>

                </td>
                <td>
                    <div>{{ $entry->trainername }}</div>
                    <div class="text-secondary">{{ $entry->trainernfid }}</div>
                    <div class="text-secondary">{{ $entry->trainerfeiid }}</div>
                </td>
                <td>
                    {{ $entry->stablename }}
                </td>
                <td>
                    {{-- @include('partials.status', ['status' => $entry->status]) --}}

                    @if ($entry->status == 'Pending' && $entry->review == '0')
                        {{ 'Pending for review' }}
                    @elseif ($entry->status == 'Pending' && $entry->review != '0')
                        {{ 'Pending for acceptance' }}
                    @else
                        {{ $entry->status }}
                    @endif
                </td>
                <td>{{ $entry->remarks }}</td>
                <td>
                    {{-- @include('partials.actions', ['object' => $entry]) --}}
                    <div>
                        @if ($entry->status == 'Eligible' ||
                            $entry->status == 'Accepted' ||
                            ($entry->status == 'Pending' && $entry->review != '0'))
                            <a id="withdrawn"
                                href="/entry/withdrawn?raceid={{ $entry->eventcode }}&entrycode={{ $entry->code }}&status=withdrawn"
                                class="btn btn-danger my-1">
                                <i class="fa-solid fa-eject"></i>
                                <span class="d-none d-xl-block">
                                    Withdrawn
                                </span>
                            </a>
                            <a id="swap"
                                href="/entry/entryswap?user={{ session()->get('profile')->userid }}&entrycode={{ $entry->code }}&raceid={{ $entry->eventcode }}"
                                class="btn btn-success my-1" id="view-entry">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                <span class="d-none d-xl-block">Swap Rider</span>
                            </a>
                            <a id="change"
                                href="/entry/entrychange?user={{ session()->get('profile')->userid }}&entrycode={{ $entry->code }}&raceid={{ $entry->eventcode }}"
                                class="btn btn-warning my-1 text-light" id="view-entry">
                                <i class="fa-solid fa-shuffle"></i>
                                <span class="d-none d-xl-block">Change Entry</span>
                            </a>
                        @else
                            No Action Available
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>
<script type="text/tpl" id="withdrawn-form">
	<p>Are you sure to withdraw?</p>
</script>
<script>
    $(document).on('click', '#withdrawn', function(e) {
        e.preventDefault();
        let self = this;
        const href = $(self).attr('href');

        $.confirm({
            title: 'Withdrawn Entry',
            columnClass: 'col-md-8',
            content: $('#withdrawn-form').html(),
            buttons: {
                'I Agree': {
                    btnClass: 'btn-success',
                    action: function() {
                        window.location.href = href
                    }
                },
                'I Disagree': {
                    btnClass: 'btn-danger',
                    action: function() {

                    }
                }
            }
        });
    });
</script>

<script type="text/tpl" id="swap-form">
	<div class="row entry align-items-center mb-2">
		<div class="col col-md">
			@include('partials.formFields.selectFormGroup', [
                'label' => 'Rider',
                'name' => 'data[__i__][rider]',
                'required' => true,
                'className' => 'rider-select select-2-basic',
                'placeholder' => 'Select a Rider',
                'keyValue' => true,
                'options' => [],
            ])
		</div>
	</div>
</script>
<script>
    // let userEefId = "{{ session()->get('profile')->userid }}";
    // let stableId = "{{ session()->get('profile')->stableid }}";
    // let rider_url = `https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[StableID]=${stableId}`;
    // if (stableId && stableId == "E0000014") {
    //     rider_url = `https://ebe.eiev-app.ae/api/uaeerf/riderlist?params[AdminUserID]=${userEefId}`;
    // }

    // $options = [
    //     'headers' => [
    //         "38948f839e704e8dbd4ea2650378a388" => "0b5e7030aa4a4ee3b1ccdd4341ca3867"
    //     ],
    // ];

    // $horseResponse = $httpClient - > request('POST', $horse_url, $options);
    // $riderResponse = $httpClient - > request('POST', $rider_url, $options);
    // $horsesJson = json_decode($horseResponse - > getBody());
    // $ridersJson = json_decode($riderResponse - > getBody());

    // $horses = $horsesJson - > horses - > data;
    // $riders = $ridersJson - > riders - > data;
    // const selected = function(className) {
    //     let data = [];
    //     for (let i = 0; i < $(`.${className}-select`).length; i++) {
    //         data.push($(document.getElementsByClassName(` ${className}-select`).item(i)).val());
    //         $(document.getElementsByClassName(` ${className}-select`).item(i)).find('option').prop('disabled',
    //             false);
    //     }

    //     for (let i = 0; i < $(`.${className}-select`).length; i++) {
    //         for (let x in data) {
    //             if ($(document.getElementsByClassName(`${className}-select`).item(i)).val() == data[x]) {
    //                 continue;
    //             }

    //             $(document.getElementsByClassName(`${className}-select`).item(i)).find('option[value="' + data[x] +
    //                 '"]').prop('disabled', true);
    //         }

    //         $($(document.getElementsByClassName(`${className}-select`).item(i)).find('option')[0]).prop('disabled',
    //             true);
    //     }
    // };
    // $('.rider-select.select-2-basic').select2({
    //     minimumInputLength: 3,
    //     ajax: {
    //         url: 'https://registration.eiev-app.ae/api/ajax/searchrider',
    //         dataType: 'json',
    //         type: 'GET',
    //         data: function(params) {
    //             console.log('params', params);
    //             let query = {
    //                 SearchFullName: params.term,
    //             }
    //             console.log(query)
    //             // Query parameters will be ?search=[term]&type=public
    //             return query;
    //         },
    //         processResults: function(data) {
    //             console.log('this', data)
    //             results = [myathletes, {
    //                 'id': 'others' || 'other',
    //                 'text': 'Other Athletes',
    //                 'children': $.map(data.riders.data, function(obj) {
    //                     obj.id = obj.riderid || obj.riderid;
    //                     obj.text = obj.text ||
    //                         `${obj.firstx0020name} ${obj.familyx0020name} (${obj.stable}) ${obj.nfx0020license} / ${obj.feix0020reg} / ${obj.countryshort}`; // replace pk with your identifier

    //                     if (riders.filter((r) => r.riderid == obj.riderid).length ==
    //                         0) {
    //                         return obj;
    //                     }
    //                 })
    //             }, ];
    //             return {
    //                 "results": results
    //             }
    //         }
    //     }
    // });
    // $(document).on('click', '#swap', function(e) {
    //     e.preventDefault();
    //     let self = this;
    //     const href = $(self).attr('href');
    //     console.log('this erntry')
    //     $.confirm({
    //         title: 'Swap Rider',
    //         columnClass: 'col-md-8',
    //         content: $('#swap-form').html(),
    //         buttons: {
    //             'I Agree': {
    //                 btnClass: 'btn-success',
    //                 action: function() {
    //                     window.location.href = href
    //                 }
    //             },
    //             'I Disagree': {
    //                 btnClass: 'btn-danger',
    //                 action: function() {

    //                 }
    //             }
    //         }
    //     });
    // });
</script>
