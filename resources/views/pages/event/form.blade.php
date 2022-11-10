
@include('partials.formFields.inputFormGroup', [
    'type' => 'text',
    'required' => true,
    'name' => 'name',
    'label' => 'EVENT TITLE',
    'value' => $event->name ? $event->name : '',
    'disabled' => $page == 'detail' ? true : false,
    'placeholder' => 'Enter event title'
])

@include('partials.formFields.textareaFormGroup', [
    'required' => true,
    'name' => 'description',
    'label' => 'EVENT DESCRIPTION',
    'placeholder' => 'Enter event description',
    'value' => $event->description,
    'disabled' => $page == 'detail' ? true : false,
])
    
@include('partials.formFields.selectFormGroup', [
    'required' => true,
    'name' => 'season_id',
    'label' => 'SEASON',
    'keyValue' => true,
    'placeholder' => $event->season && $page == 'detail' ? $event->season->season : 'Select a SEASON',
    'disabled' => $page == 'detail' ? true : false,
    'options' => $page != 'detail' ? $seasons : '',
    'value' => $event->season_id ? $event->season_id : null,
])
<div class="row">
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'required' => true,
                  'name' => 'location',
                  'label' => 'LOCATION',
                  'placeholder' => $event->location && $page == 'detail' ? $event->location : 'Select a Location',
                  'disabled' => $page == 'detail' ? true : false,
                  'options' => $page != 'detail' ? $locations : [],
                  'value' => $event->location ?? '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'required' => true,
                  'name' => 'country',
                  'label' => 'COUNTRY',
                  'options' => $page == 'detail' ? [] : $countries,
                  'keyValue' => true,
                  'placeholder' => $event->country && $page == 'detail' ? $event->country : 'Select a country',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $event->country ?? ''
        
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'required' => true,
                  'name' => 'start_date',
                  'label' => 'START DATE',
                  'idName' => 'start-date',
                  'value' => date('Y-m-d', strtotime($event->start_date)),
                  'disabled' => $page == 'detail' ? true : false,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'required' => true,
                  'name' => 'end_date',
                  'label' => 'END DATE',
                  'idName' => 'end-date',
                  'value' => date('Y-m-d', strtotime($event->end_date)),
                  'disabled' => $page == 'detail' ? true : false,
              ])
	</div>
</div>
@section('custom-script')
    <script>
	 $('#start-date, #end-date').daterangepicker({
		 singleDatePicker: true,
		 locale: {
			 format: 'Y-MM-DD'
		 }
	 });
	</script>
@endsection
