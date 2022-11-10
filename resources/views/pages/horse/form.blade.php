<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'name',
            'label' => 'Name',
            'value' => $horse->name ? $horse->name : '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter horse name'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'originalName',
            'label' => 'Original Name',
            'value' => $horse->originalName ? $horse->originalName : '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter original name'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.selectFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'countryOfBirth',
            'label' => 'Country of Birth',
            'value' => $horse->countryOfBirth ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Select country of birth',
            'options' => $countries
        ])
	</div>
</div>
<div class="row">
	<div class="col">
        @include('partials.formFields.selectFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'breed',
            'label' => 'Breed',
            'value' => $horse->breed ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Select horse breed',
            'options' => $breeds,
            'keyValue' => true,
        ])
	</div>
    <div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'breeder',
            'label' => 'Breeder',
            'value' => $horse->breeder ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter Breeder'
        ])
	</div>
    <div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'birthday',
            'label' => 'Date of Birth',
            'value' => $horse->birthday ?? '',
            'idName' => 'birthday',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter birthday'
        ])
	</div>
</div>
<div class="row">
	<div class="col">
        @include('partials.formFields.selectFormGroup', [
            'type' => 'text',
            'name' => 'gender',
            'label' => 'Gender',
		    'required' => true,
            'keyValue' => true,
            'value' => $horse->gender ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Select Gender',
		    'options' => $genders,
        ])
	</div>
    <div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
            'name' => 'colour',
            'label' => 'Colour',
            'value' => $horse->colour ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter colour'
        ])
	</div>
    <div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'microchipNo',
            'label' => 'Microchip No',
            'value' => $horse->microchipNo ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter microchip No.'
        ])
	</div>
</div>
<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'uelnNo',
            'label' => 'UELN No',
            'value' => $horse->uelnNo ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter UELN No.'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.selectFormGroup', [
            'type' => 'text',
            'required' => true,
            'name' => 'countryOfResidence',
            'label' => 'Country of Residence',
            'value' => $horse->countryOfResidence ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Select country of residence',
            'options' => $countries
        ])
	</div>
</div>
<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'sire',
            'label' => 'Sire',
            'value' => $horse->sire ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter Sire'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'dam',
            'label' => 'Dam',
            'value' => $horse->dam ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter Dam'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'sireOfDam',
            'label' => 'Sire of Dam',
            'value' => $horse->sireOfDam ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter Sire of Dam'
        ])
	</div>
</div>
<br />
<h2 class="text-danger">FEI Registration</h2>
<div class="row">
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'feiPassportNo',
            'label' => 'FEI Passport No',
            'value' => $horse->feiPassportNo ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter FEI Passport No'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'feiExpireDate',
            'label' => 'FEI Expiry Date',
            'value' => $horse->feiExpireDate ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter FEI Expiry Date',
            'idName' => 'fei-expire-date'
        ])
	</div>
	<div class="col">
        @include('partials.formFields.inputFormGroup', [
            'type' => 'text',
		    'required' => true,
            'name' => 'feiRegNo',
            'label' => 'FEI Registration No',
            'value' => $horse->feiRegNo ?? '',
            'disabled' => $page == 'detail' ? true : false,
            'placeholder' => 'Enter FEI Registration No',
        ])
	</div>
</div>
<br />
<h2 class="text-danger">Owner Information</h2>
@include('partials.formFields.selectFormGroup', [
    'type' => 'text',
    'required' => true,
    'name' => 'owner_id',
    'keyValue' => true,
    'label' => 'Owner',
    'value' => $horse->owner_id ?? '',
    'disabled' => $page == 'detail' ? true : false,
    'placeholder' => 'Select owner',
    'options' => $owners,
])
<br />
<h2 class="text-danger">Trainer Information</h2>
@include('partials.formFields.selectFormGroup', [
    'type' => 'text',
    'required' => true,
    'keyValue' => true,
    'name' => 'trainer_id',
    'label' => 'Trainer',
    'value' => $horse->trainer_id ?? '',
    'disabled' => $page == 'detail' ? true : false,
    'placeholder' => 'Select trainer',
    'options' => $trainers,
])

<br />
@include('partials.formFields.textareaFormGroup', [
    'name' => 'remarks',
    'label' => 'REMARKS',
    'disabled' => $page == 'detail' ? true : false,
    'value' => $horse->remarks
])
    
@section('custom-script')
    <script>
	 $('#birthday, #fei-expire-date').daterangepicker({
		 singleDatePicker: true,
		 locale: {
			 format: 'Y-MM-DD'
		 }
	 });
	</script>
@endsection
	
