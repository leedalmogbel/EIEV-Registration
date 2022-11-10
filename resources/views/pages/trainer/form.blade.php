<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'emiratesId',
                  'label' => 'EMIRATES ID',
                  'required' => true,
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $trainer->emiratesId
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'discipline',
                  'label' => 'DISCIPLINE',
                  'keyValue' => true,
                  'placeholder' => $page == 'detail' && $trainer->discipline ? $trainer->realDiscipline() : 'Select a Discipline',
                  'disabled' => $page == 'detail' ? true : false,
                  'required' => true,
                  'options' => $page == 'detail' ? [] : $discipline,
                  'value' => $trainer->discipline ?? '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'feiRegistrationNo',
                  'label' => 'FEI REGISTRATION NUMBER',
                  'disabled' => $page == 'detail' ? true : false,
                  'required' => true,
                  'value' => $trainer->feiRegistrationNo,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'feiRegistrationDate',
                  'label' => 'FEI REGISTRATION DATE',
                  'idName' => 'fei-date',
                  'disabled' => $page == 'detail' ? true : false,
                  'required' => true,
                  'value' => $trainer->feiRegistrationDate
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'visaType',
                  'label' => 'VISA CATEGORY',
                  'keyValue' => true,
                  'required' => true,
                  'placeholder' => $page == 'detail' && $trainer->visaType ? $trainer->realVisaType() : 'Select a Visa Category',
                  'options' => $page == 'detail' ? [] : $visa_types,
                  'value' => $trainer->visaType,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'firstname',
                  'label' => 'FIRSTNAME',
                  'required' => true,
                  'value' => $trainer->firstname,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'lastname',
                  'label' => 'LASTNAME',
                  'required' => true,
                  'value' => $trainer->lastname,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'nationality',
                  'label' => 'NATIONALITY',
                  'required' => true,
                  'value' => $trainer->nationality,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'uaeAddress[address]',
                  'label' => 'UAE ADDRESS',
                  'value' => isset($trainer->uaeAddress['address']) ? $trainer->uaeAddress['address'] : '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'uaeAddress[city]',
                  'disabled' => $page == 'detail' ? true : false,
                  'label' => 'UAE CITY',
                  'placeholder' => $page == 'detail' && isset($trainer->uaeAddress['city']) ? $trainer->uaeAddress['city'] : 'Select a UAE City',
                  'options' => $page == 'detail' ? [] : ['Abu Dhabi', 'Dubai', 'Al Ain', 'Ajman', 'Sharjah', 'Ras Al Kaimah', 'Fujaira', 'Umm All Quwain'],
                  'value' => isset($trainer->uaeAddress['city']) ? $trainer->uaeAddress['city'] : null,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'uaeAddress[country]',
                  'label' => 'UAE Country',
                  'disabled' => $page == 'detail' ? true : false,
                  'placeholder' => isset($trainer->uaeAddress['country']) && $page == 'detail' ? $trainer->uaeAddress['country'] : 'Select a Country',
                  'options' => ['UAE' => 'United Arab Emirates'],
                  'keyValue' => true,
                  'value' => isset($trainer->uaeAddress['country']) ? $trainer->uaeAddress['country'] : '',
              ])
	</div>
</div>
<br>
<h2 class="text-danger">Contact Information</h2>
<br />
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'email',
                  'label' => 'EMAIL',
                  'required' => true,
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $trainer->email,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'phone',
                  'label' => 'PHONE NO.',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $trainer->phone,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'mobile',
                  'label' => 'MOBILE NO.',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $trainer->mobile,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'homeAddress[address]',
                  'label' => 'HOME ADDRESS',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => isset($trainer->homeAddress['address']) ? $trainer->homeAddress['address'] : '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'homeAddress[city]',
                  'label' => 'HOME CITY',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => isset($trainer->homeAddress['city']) ? $trainer->homeAddress['city'] : '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'homeAddress[country]',
                  'label' => 'HOME COUNTRY',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => isset($trainer->homeAddress['country']) ? $trainer->homeAddress['country'] : '',
              ])
	</div>
</div>
<br />
@include('partials.formFields.textareaFormGroup', [
    'name' => 'remarks',
    'label' => 'REMARKS',
    'disabled' => $page == 'detail' ? true : false,
    'value' => $trainer->remarks
])
    
@section('custom-script')
    <script>
	 $('#fei-date').daterangepicker({
		 singleDatePicker: true,
		 locale: {
			 format: 'Y-MM-DD'
		 }
	 });
	</script>
@endsection
