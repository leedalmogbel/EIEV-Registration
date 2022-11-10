@php
$raceStables = $race->raceStables->toArray();
@endphp
<h2 class="text-danger">Event Information</h2>
@include('partials.formFields.selectFormGroup',[
    'required' => true,
    'name' => 'event_id',
    'label' => 'Event',
    'keyValue' => true,
    'idName' => 'race-event',
    'placeholder' => 'Select an Event',
    'disabled' => $page == 'detail' ? true : false,
    'options' => $events,
    'value' => $race->event_id,
])

<div class="event-form{{old('event_id') && old('event_id') == 'new' ? '': ' hidden'}}">
    @include('partials.formFields.inputFormGroup', [
        'type' => 'text',
        'required' => true,
        'name' => 'event[name]',
        'label' => 'Event Title',
        'placeholder' => 'Enter event title'
    ])

    @include('partials.formFields.textareaFormGroup', [
        'required' => true,
        'name' => 'event[description]',
        'label' => 'EVENT DESCRIPTION',
        'placeholder' => 'Enter event description',
    ])
    
    @include('partials.formFields.selectFormGroup', [
        'required' => true,
        'name' => 'event[season_id]',
        'label' => 'SEASON',
        'keyValue' => true,
        'placeholder' => 'Select a SEASON',
        'options' => $seasons,
    ])
        
	<div class="row">
		<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'required' => true,
                  'name' => 'event[location]',
                  'label' => 'LOCATION',
                  'placeholder' => 'Select a Location',
                  'options' => $locations,
              ])
		</div>
		<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'required' => true,
                  'name' => 'event[country]',
                  'label' => 'COUNTRY',
                  'keyValue' => true,
                  'placeholder' => 'Select a country',
			      'options' => $countries,
			
              ])
		</div>
	</div>
	<div class="row">
		<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'required' => true,
                  'name' => 'event[start_date]',
                  'label' => 'START DATE',
                  'idName' => 'start-date',
              ])
		</div>
		<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'required' => true,
                  'name' => 'event[end_date]',
                  'label' => 'END DATE',
                  'idName' => 'end-date',
              ])
		</div>
	</div>
</div>
<br />
<h2 class="text-danger">Race Information</h2>
@include('partials.formFields.inputFormGroup', [
    'type' => 'text',
    'required' => true,
    'name' => 'title',
    'label' => 'Race Title',
    'placeholder' => 'Enter event title',
    'value' => $race->title,
    'disabled' => $page == 'detail' ? true : false,
])
<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'contact[person]',
            'label' => 'Contact Person',
            'placeholder' => 'Enter contact person',
            'value' => $race->contact['person'] ?? '',
            'disabled' => $page == 'detail' ? true : false,
        ])
    </div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'contact[number]',
            'label' => 'Contact Number',
            'placeholder' => 'Enter contact number',
            'value' => $race->contact['number'] ?? '',
            'disabled' => $page == 'detail' ? true : false,
        ])
    </div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'number',
            'required' => true,
            'name' => 'entryCount',
            'label' => 'Entry Count',
            'placeholder' => 'Enter entry count',
            'value' => $race->entryCount ?? '',
            'disabled' => $page == 'detail' ? true : false,
        ])
    </div>
</div>
<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'date',
            'label' => 'Race Date',
            'value' => $race->date ?? '',
		    'idName' => 'race-date',
            'disabled' => $page == 'detail' ? true : false,
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'opening',
            'label' => 'Opening Date',
            'value' => $race->opening ?? '',
		    'idName' => 'opening-date',
            'disabled' => $page == 'detail' ? true : false,
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'closing',
            'label' => 'Closing Date',
            'value' => $race->closing ?? '',
		    'idName' => 'closing-date',
            'disabled' => $page == 'detail' ? true : false,
        ])
	</div>
</div>
<label>Pledge</label>
<div class="mb-2">
	<input
        type="checkbox"
        name="pledge[is_pledge]"
		value="true"
		id="is-pledge"
        @if (!empty($race->pledge))
		checked
		@endif

	/>
</div>
<div class="pledge-inputs{{empty($race->pledge) ? ' hidden' : ''}}">
	@include('partials.formFields.inputFormGroup', [
        'type' => 'text',
        'name' => 'pledge[notice][title]',
        'placeholder' => 'Enter Notice Title',
        'label' => 'Notice Title',
        'disabled' => $page == 'detail' ? true : false,
        'value' => isset($race->pledge['notice']['title']) ? $race->pledge['notice']['title'] : '',
    ])
        
	@include('partials.formFields.textareaFormGroup', [
        'type' => 'text',
        'name' => 'pledge[notice][body]',
        'placeholder' => 'Enter Notice Body',
        'label' => 'Notice Body',
        'disabled' => $page == 'detail' ? true : false,
        'value' => isset($race->pledge['notice']['body']) ? $race->pledge['notice']['body'] : '',
    ])
</div>
<br />
<h2 class="text-danger">Stable Details</h2>
<div class="row">
	<div class="col">
		@include('partials.formFields.inputFormGroup', [
            'label' => 'Sheikh Stable',
            'name' => 'sheikhStable',
            'required' => true,
            'value' => $race->sheikhStable ?? '1',
            'type' => 'number',
            'disabled' => $page == 'detail' ? true : false,
        ])
	</div>
	<div class="col">
		@include('partials.formFields.inputFormGroup', [
            'label' => 'Private Stable',
            'name' => 'privateStable',
            'required' => true,
            'value' => $race->privateStable ?? '1',
            'type' => 'number',
            'disabled' => $page == 'detail' ? true : false,
        ])
	</div>
</div>
<label>Specific</label>
<div>
	<input
		type="checkbox"
		value="true"
		id="is-specific"
		{{!empty($raceStables) ? 'checked' : ''}}
	/>
</div>
<br />
<div class="stables-specific{{!empty($raceStables) ? '' : ' hidden'}}">
    <div class="stables-list">
		@if ($raceStables && !empty($raceStables))
			@foreach ($raceStables as $raceStable)
				<div class="row">
					<div class="col">
                        @include('partials.formFields.selectFormGroup', [
                            'name' => 'stable_specific[stable_id][]',
                            'placeholder' => 'Select stable',
                            'keyValue' => true,
                            'options' => $stables,
                            'label' => 'Stable',
                            'className' => 'stable-select',
                            'value' => $raceStable['stable_id'],
                            'disabled' => $page == 'detail' ? true : false,
                        ])
					</div>
					<div class="col">
   			            @include('partials.formFields.inputFormGroup', [
                            'name' => 'stable_specific[entryCount][]',
                            'placeholder' => 'Enter entry count',
                            'type' => 'number',
                            'label' => 'Entry count',
                            'value' => $raceStable['entryCount'],
                            'disabled' => $page == 'detail' ? true : false,
                        ])
					</div>
					<div class="col-1"><a href="#" class="btn btn-danger remove-this-stable">&times;</a></div>
				</div>
			@endforeach
		@else
		<div class="row">
			<div class="col">
            @include('partials.formFields.selectFormGroup', [
                'name' => 'stable_specific[stable_id][]',
                'placeholder' => 'Select stable',
                'keyValue' => true,
                'options' => $stables,
                'label' => 'Stable',
				'className' => 'stable-select',
            ])
			</div>
			<div class="col">
			@include('partials.formFields.inputFormGroup', [
                'name' => 'stable_specific[entryCount][]',
                'placeholder' => 'Enter entry count',
                'type' => 'number',
                'label' => 'Entry count',
            ])
			</div>
			<div class="col-1"><a href="#" class="btn btn-danger remove-this-stable">&times;</a></div>
		</div>
		@endif
	</div>
	@if ($page != 'detail')
		<a href="#" class="btn btn-main" id="add-stable">+ Add Stable</a>
	@endif
</div>
<br />
@include('partials.formFields.textareaFormGroup', [
    'label' => 'Race Description',
    'name' => 'description',
    'placeholder' => 'Enter race description',
    'value' => $race->description ?? '',
    'disabled' => $page == 'detail' ? true : false,
])
@section('custom-script')
<script id="stable-tpl" type="text/tpl">
		<div class="row">
			<div class="col">
            @include('partials.formFields.selectFormGroup', [
                'name' => 'stable_specific[stable_id][]',
                'placeholder' => 'Select stable',
                'keyValue' => true,
                'options' => $stables,
                'label' => 'Stable',
	            'className' => 'stable-select',
            ])
			</div>
			<div class="col">
			@include('partials.formFields.inputFormGroup', [
                'name' => 'stable_specific[entryCount][]',
                'placeholder' => 'Enter entry count',
                'type' => 'number',
                'label' => 'Entry count',
            ])
			</div>
			<div class="col-1"><a href="#" class="btn btn-danger remove-this-stable">&times;</a></div>
		</div>
</script>
<script>

 const createEvent = (create) => {
	 if (create) {
		 $('.event-form').removeClass('hidden');
		 return;
	 }
	 
     $('.event-form').addClass('hidden');
     return;
 };

 const  stableSelected = function () {
	 let stables = [];
	 for(let i = 0; i < $('.stable-select').length; i++) {
		 stables.push($(document.getElementsByClassName('stable-select').item(i)).val());
		 $(document.getElementsByClassName('stable-select').item(i)).find('option').prop('disabled', false);
	 }

	 
	 for(let i = 0; i < $('.stable-select').length; i++) {
		 for(let x in stables) {
			 if($(document.getElementsByClassName('stable-select').item(i)).val() == stables[x]) {
				 continue;
			 }
			 
			 $(document.getElementsByClassName('stable-select').item(i)).find('option[value="'+ stables[x] +'"]').prop('disabled', true);
		 }
		 
		 $($(document.getElementsByClassName('stable-select').item(i)).find('option')[0]).prop('disabled', true);
	 }
 };

 $(document).on('change', '.stable-select', function() {
	 stableSelected.call();
	 $(this).find('option').removeClass('selected');
	 $(this).find('option[value="' + $(this).val() + '"]').addClass('selected');
	 $('.stable-select').find('option[value="' + $(this).val() + '"]').prop('disabled', true);
	 $(this).find('.selected').prop('disabled', false);
	 
 });

 $(document).on('click', '.remove-this-stable', function(e) {
	 e.preventDefault();
	 $(this).parent().parent().remove();
	 stableSelected.call();
 })

 $('#is-specific').change(function () {
	 if ($(this).is(':checked')) {
		 $('.stables-specific').removeClass('hidden');
		 $('.stables-specific').find('.form-control').prop('disabled', false);
		 return;
	 }
	 
	 $('.stables-specific').addClass('hidden');
	 $('.stables-specific').find('.form-control').prop('disabled', true);
	 return;
 });

 $('#add-stable').click(function (e) {
	 e.preventDefault();
	 let tpl = $('#stable-tpl').html();
	 $('.stables-list').append(tpl);
	 stableSelected.call();
 });

 $('#is-pledge').change(function () {
	 if($(this).is(':checked')) {
		 $('.pledge-inputs').removeClass('hidden');
		 return;
	 }

	 
	 $('.pledge-inputs').addClass('hidden');
	 return;
 });

 $('#race-event').change(function (){
	 let create = false
	 if ($(this).val() == 'new') {
		 create = true;
	 }

	 createEvent(create);
	 
 }).find('[value="new"]').css({
	 'font-weight': 'bold',
	 'color' : '#000',
 });
 
 $('#start-date, #end-date, #race-date, #opening-date, #closing-date').daterangepicker({
	 singleDatePicker: true,
	 locale: {
		 format: 'Y-MM-DD'
	 }
 });

 stableSelected();
</script>
@endsection
