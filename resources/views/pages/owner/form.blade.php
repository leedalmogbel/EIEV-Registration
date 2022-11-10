<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'emiratesId',
                  'label' => 'EMIRATES ID',
                  'required' => true,
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $owner->emiratesId,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'discipline',
                  'label' => 'DISCIPLINE',
                  'required' => true,
                  'keyValue' => true,
                  'placeholder' => $page == 'detail' && $owner->discipline ? $owner->realDiscipline() : 'Select a Discipline',
                  'disabled' => $page == 'detail' ? true : false,
                  'options' => $page == 'detail' ? [] : $discipline,
                  'value' => $owner->discipline ?? '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'feiRegistrationNo',
                  'required' => true,
                  'label' => 'FEI REGISTRATION NUMBER',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $owner->feiRegistrationNo,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'name' => 'feiRegistrationDate',
                  'required' => true,
                  'label' => 'FEI REGISTRATION DATE',
                  'idName' => 'fei-date',
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $owner->feiRegistrationDate
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'visaType',
                  'label' => 'VISA CATEGORY',
                  'required' => true,
                  'keyValue' => true,
                  'placeholder' => $page == 'detail' && $owner->visaType ? $owner->realVisaType() : 'Select a Visa Category',
                  'options' => $page == 'detail' ? [] : $visa_types,
                  'value' => $owner->visaType,
              ])
	</div>
</div>
<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'firstname',
                  'required' => true,
                  'label' => 'FIRSTNAME',
                  'value' => $owner->firstname,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'lastname',
                  'label' => 'LASTNAME',
                  'required' => true,
                  'value' => $owner->lastname,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'disabled' => $page == 'detail' ? true : false,
                  'name' => 'nationality',
                  'label' => 'NATIONALITY',
                  'required' => true,
                  'value' => $owner->nationality,
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
                  'value' => isset($owner->uaeAddress['address']) ? $owner->uaeAddress['address'] : '',
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'uaeAddress[city]',
                  'disabled' => $page == 'detail' ? true : false,
                  'label' => 'UAE CITY',
                  'placeholder' => $page == 'detail' && isset($owner->uaeAddress['city']) ? $owner->uaeAddress['city'] : 'Select a UAE City',
                  'options' => $page == 'detail' ? [] : ['Abu Dhabi', 'Dubai', 'Al Ain', 'Ajman', 'Sharjah', 'Ras Al Kaimah', 'Fujaira', 'Umm All Quwain'],
                  'value' => isset($owner->uaeAddress['city']) ? $owner->uaeAddress['city'] : null,
              ])
	</div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'name' => 'uaeAddress[country]',
                  'label' => 'UAE Country',
                  'disabled' => $page == 'detail' ? true : false,
                  'placeholder' => isset($owner->uaeAddress['country']) && $page == 'detail' ? $owner->uaeAddress['country'] : 'Select a Country',
                  'options' => ['UAE' => 'United Arab Emirates'],
                  'keyValue' => true,
                  'value' => isset($owner->uaeAddress['country']) ? $owner->uaeAddress['country'] : ''
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
            'value' => $owner->email,
        ])
            </div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'phone',
            'label' => 'PHONE NO.',
            'disabled' => $page == 'detail' ? true : false,
            'value' => $owner->phone,
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'mobile',
            'label' => 'MOBILE NO.',
            'disabled' => $page == 'detail' ? true : false,
            'value' => $owner->mobile,
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
            'value' => isset($owner->homeAddress['address']) ? $owner->homeAddress['address'] : '',
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'homeAddress[city]',
            'label' => 'HOME CITY',
            'disabled' => $page == 'detail' ? true : false,
            'value' => isset($owner->homeAddress['city']) ? $owner->homeAddress['city'] : '',
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'homeAddress[country]',
            'label' => 'HOME COUNTRY',
            'disabled' => $page == 'detail' ? true : false,
            'value' => isset($owner->homeAddress['country']) ? $owner->homeAddress['country'] : '',
        ])
	</div>
</div>
<br />
@include('partials.formFields.textareaFormGroup', [
    'name' => 'remarks',
    'label' => 'REMARKS',
    'disabled' => $page == 'detail' ? true : false,
    'value' => $owner->remarks
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
