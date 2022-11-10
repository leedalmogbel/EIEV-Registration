
@include('partials.formFields.inputFormGroup', [
    'type' => 'text',
    'name' => 'season',
    'label' => 'SEASON',
    'value' => $season->season,
    'disabled' => $page == 'detail' ? true : false,
    'required' => true,
    'placeholder' => 'Enter season name'
])
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'value' => $season->start_date,
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'start_date',
                  'label' => 'START DATE',
                  'required' => true,
                  'idName' => 'start-date'
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'end_date',
                  'value' => $season->end_date,
                  'disabled' => $page == 'detail' ? true : false,
                  'label' => 'END DATE',
                  'required' => true,
                  'idName' => 'end-date'
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
