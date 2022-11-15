<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <h2 class="text-danger">Race / Event Detail</h2>
@include('partials.formFields.selectFormGroup', [
    'label' => 'Race / Event',
    'name' => 'race_id',
    'required' => true,
    'keyValue' => true,
    'value' => request()->get('race_id') ? request()->get('race_id') : null,
    'placeholder' => 'Select an Event/Race',
    'options' => $races,
])
<br />
<h2 class="text-danger">User Detail</h2>
@include('partials.formFields.selectFormGroup', [
    'label' => 'User',
    'name' => 'user_id',
    'required' => true,
    'keyValue' => true,
    'placeholder' => 'Select a User',
    'options' => $users,
    'idName' => 'select-user',
    --}}
<br />
<h2 class="text-danger">Entry Detail</h2>
<div class="entries">
    @if (old('user_id'))
        @if (old('data') && is_array(old('data')))
            @foreach (old('data') as $index => $data)
                {{ $index }}
                <div class="row entry">
                    <div class="col">
                        <div><small class="text-danger">You can't select expired horses</small></div>
                        @include('partials.formFields.selectFormGroup', [
                            'label' => 'Horse',
                            'name' => "data[$index][horse]",
                            'required' => true,
                            'placeholder' => 'Select a Horse',
                            'className' => 'horse-select select-2-basic',
                            'keyValue' => true,
                            'options' => $horses,
                        ])
                    </div>
                    <div class="col">
                    <div><small class="text-danger">You can't select expired riders</small></div>
                        @include('partials.formFields.selectFormGroup', [
                            'label' => 'Rider',
                            'name' => "data[$index][rider]",
                            'required' => true,
                            'className' => 'rider-select select-2-basic',
                            'placeholder' => 'Select a Rider',
                            'keyValue' => true,
                            'options' => $riders,
                        ])
                    </div>
                    <div class="col-1">
                        <a href="#" class="btn btn-danger remove-this">&times;</a>
                    </div>
                </div>
            @endforeach
        @endif
    @else
        <p><em>Fetching horses and riders...</em></p>
    @endif
</div>
<a href="#" class="btn btn-main{{ old('user_id') ? '' : ' hidden' }}" id="add-entry"><i
        class="fa-solid fa-plus"></i> Add Another</a>

<script type="text/tpl" id="rules-content">
 <p>Lorem ipsum dolor sit amet, possim facilisis iracundia mea ut, usu eu malorum eripuit democritum. Cu veritus facilisi mel, elit dicat expetendis ad his. In putant possim aperiri nec, ius purto corpora instructior ne. At cum tota indoctum vituperatoribus, an eius meliore conceptam his. Sea id sint nostro causae, ne eius veri nam, nam et fuisset accusamus. Partem nemore facilis mei eu, ubique officiis intellegam ei nam. Ea quo duis graeco expetenda, blandit epicurei has ad, cum modo dicat inciderint ut. Sit ea summo adipisci. Est eu mucius praesent, qui ceteros prodesset te. Duis novum dicam sea no, vim an scripta accusata vulputate. Agam eius an eos, decore cetero suscipit ne sed. Eu invidunt instructior mea, purto suavitate definiebas sed et. Nullam apeirian at eos. Veritus invidunt an eum, decore nostrum consequat quo ut. Discere delicata accusamus mea ei. Eu menandri instructior sit. Id has albucius splendide. Erant constituto ius in, legendos salutatus qui at, voluptatum assueverit sed at. Eu nihil docendi noluisse duo, cu zril petentium ius. Everti iudicabit no sit. Eu eum error eirmod, nec veri posse simul at. An animal facilis pri.</p>
 <p class="alert alert-warning" role="alert"><i class="fa-solid fa-circle-info"></i> If you agree on this, All of the rules here will be accepted by you.</p>
</script>
<script type="text/tpl" id="horse-rider">
	<div class="row entry">
		<div class="col">
			@include('partials.formFields.selectFormGroup', [
                'label' => 'Horse',
                'name' => 'data[__i__][horse]',
                'required' => true,
                'placeholder' => 'Select a Horse',
                'className' => 'horse-select select-2-basic',
                'keyValue' => true,
                'options' => [],
            ])
		</div>
		<div class="col">
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
        <div class="col-1">
			<a href="#" class="btn btn-danger remove-this">&times;</a>
		</div>
	</div>
</script>
@section('custom-script')
    <script>
        let entryCount = 0;
        let horses = JSON.parse('{!! $jsonHorse !!}');
        let riders = JSON.parse('{!! $jsonRider !!}');
        const selected = function(className) {
            let data = [];
            for (let i = 0; i < $(`.${className}-select`).length; i++) {
                data.push($(document.getElementsByClassName(` ${className}-select`).item(i)).val());
                $(document.getElementsByClassName(` ${className}-select`).item(i)).find('option').prop('disabled',
                    false);
            }

            for (let i = 0; i < $(`.${className}-select`).length; i++) {
                for (let x in data) {
                    if ($(document.getElementsByClassName(`${className}-select`).item(i)).val() == data[x]) {
                        continue;
                    }

                    $(document.getElementsByClassName(`${className}-select`).item(i)).find('option[value="' + data[x] +
                        '"]').prop('disabled', true);
                }

                $($(document.getElementsByClassName(`${className}-select`).item(i)).find('option')[0]).prop('disabled',
                    true);
            }
        };

        const addEntry = function() {
            let tpl = $('#horse-rider').html().replace(/__i__/g, entryCount);
            let horseHtml = '<option disabled selected>Select a Horse</option>';
            tpl = $(tpl);

            for (let i in horses) {
                //  horseHtml += `<option value="${horses[i].horse_id}">${horses[i].name}</option>`
                horseHtml +=
                    `<option value="${horses[i].horseid}">${horses[i].name} / ${horses[i].nfregistration} / ${horses[i].gender} / ${horses[i].color}</option>`
            }

            let riderHtml = '<option disabled selected>Select a Rider</option>';
            console.log('riders', riders)
            for (let i in riders) {
                riderHtml +=
                    `<option value="${riders[i].riderid}">${riders[i].firstx0020name} ${riders[i].familyx0020name} (${riders[i].stable}) ${riders[i].nfx0020license} / ${riders[i].feix0020reg} / ${riders[i].countryshort}</option>`
            }

            tpl.find('.horse-select').html(horseHtml);
            tpl.find('.rider-select').html(riderHtml);

            $('.entries').append(tpl);

            entryCount++;
            selected.call(this, 'rider');
            selected.call(this, 'horse');
            let userEefId = "{{ session()->get('profile')->userid }}";
            let stableId = "{{ session()->get('profile')->stableid }}";
            console.log('Stablid', stableId)
            let myathletes = {
                'id': 'riders' || 'riders',
                'text': 'My Athletes',
                'children': $.map(riders, function(obj) {
                    obj.id = obj.riderid || obj.riderid;
                    obj.text = obj.text ||
                        `${obj.firstx0020name} ${obj.familyx0020name} (${obj.stable}) ${obj.nfx0020license} / ${obj.feix0020reg} / ${obj.countryshort}`; // replace pk with your identifier

                    return obj;
                })
            };

            $('.horse-select.select-2-basic').select2();
            $('.rider-select.select-2-basic').select2({
                minimumInputLength: 3,
                ajax: {
                    url: 'https://registration.eiev-app.ae/api/ajax/searchrider',
                    dataType: 'json',
                    type: 'GET',
                    data: function(params) {
                        console.log('params', params);
                        let query = {
                            SearchFullName: params.term,
                            // StableID: stableId,
                        }
                        // if (stableId === 'E0000014') {
                        //     query = {
                        //         SearchFullName: params.term,
                        //         AdminUserID: userEefId
                        //     }
                        // }
                        console.log(query)
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        console.log('this', data)
                        results = [myathletes, {
                            'id': 'others' || 'other',
                            'text': 'Other Athletes',
                            'children': $.map(data.riders.data, function(obj) {
                                obj.id = obj.riderid || obj.riderid;
                                obj.text = obj.text ||
                                    `${obj.firstx0020name} ${obj.familyx0020name} (${obj.stable}) ${obj.nfx0020license} / ${obj.feix0020reg} / ${obj.countryshort}`; // replace pk with your identifier

                                if (riders.filter((r) => r.riderid == obj.riderid).length ==
                                    0) {
                                    return obj;
                                }
                            })
                        }, ];
                        return {
                            "results": results
                            // "results": results
                            // results: [{
                            //     riderid: data.riders.data[0].riderid,
                            //     firstx0020name: data.riders.data[0].firstx0020name,
                            //     familyx0020name: data.riders.data[0].familyx0020name,
                            //     stable: data.riders.data[0].stable,
                            //     nfx0020license: data.riders.data[0].nfx0020license,
                            //     feix0020reg: data.riders.data[0].feix0020reg,
                            //     countryshort: data.riders.data[0].countryshort
                            // }]
                            // results: $.map(riders, function(obj) {
                            //     console.log('obj', obj)
                            //     return {
                            //         riderid: obj.riderid,
                            //         firstx0020name: obj.firstx0020name,
                            //         familyx0020name: obj.familyx0020name,
                            //         stable: obj.stable,
                            //         nfx0020license: obj.nfx0020license,
                            //         feix0020reg: obj.feix0020reg,
                            //         countryshort: obj.countryshort
                            //     };
                            // })
                        }
                    }
                }
            });

        };

        const recalculateIndex = function() {
            let c = $('.entries .entry').length;
            for (let i = 0; i < c; i++) {
                $($('.entries .entry')[i]).find('.horse-select').attr('name', 'data[' + i + '][horse]');
                $($('.entries .entry')[i]).find('.rider-select').attr('name', 'data[' + i + '][rider]');
            }

            entryCount = c;
        };

        $('#select-user').change(function() {

            let userId = $(this).val();
            $.get(`/entry/user/${userId}`, function(res) {
                $('#add-entry').removeClass('hidden');
                $('.entries').html(''); // reset entries
                riders = res.riders;
                horses = res.horses;
                addEntry();
            }).fail(function() {
                $.alert('Something went wrong!');
            });
        });

        $('#add-entry').click(function(e) {
            e.preventDefault();
            addEntry();
        });

        $(document).on('click', '.remove-this', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
            recalculateIndex();
            selected.call(this, 'horse');
            selected.call(this, 'rider');
        });

        $(document).on('change', '.horse-select', function() {
            selected.call(this, 'horse');
            $(this).find('option').removeClass('selected');
            $(this).find('option[value="' + $(this).val() + '"]').addClass('selected');
            $('.horse-select').find('option[value="' + $(this).val() + '"]').prop('disabled', true);
            $(this).find('.selected').prop('disabled', false);
        });

        $(document).on('change', '.rider-select', function() {
            selected.call(this, 'rider');
            $(this).find('option').removeClass('selected');
            $(this).find('option[value="' + $(this).val() + '"]').addClass('selected');
            $('.rider-select').find('option[value="' + $(this).val() + '"]').prop('disabled', true);
            $(this).find('.selected').prop('disabled', false);
        });

        let userId = "{{ session()->get('user')['user_id'] }}";
        $.get(`/entry/user/${userId}`, function(res) {
            $('#add-entry').removeClass('hidden');
            $('.entries').html(''); // reset entries
            riders = res.riders;
            horses = res.horses;
            addEntry();
        }).fail(function() {
            $.alert('Something went wrong!');
        });

        // $('.select-2-basic').select2({
        //     minimumInputLength: 3
        // });
    </script>
@endsection
