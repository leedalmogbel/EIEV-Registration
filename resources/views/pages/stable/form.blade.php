<div class="row">
	<div class="col">
              @include('partials.formFields.inputFormGroup', [
                  'type' => 'text',
                  'required' => true,
                  'name' => 'name',
                  'label' => 'STABLE NAME',
                  'placeholder' => 'Enter stable name',
                  'value' => $stable->name,
                  'disabled' => $page == 'detail' ? true : false,
              ])
    </div>
	<div class="col">
              @include('partials.formFields.selectFormGroup', [
                  'required' => true,
                  'name' => 'type',
                  'label' => 'STABLE TYPE',
                  'keyValue' => true,
                  'placeholder' => $page == 'detail' && $stable->type ? $stable->realType() : 'Selec a stable type',
                  'options' => $page == 'detail' ? [] : $types,
                  'disabled' => $page == 'detail' ? true : false,
                  'value' => $stable->type ?? '',
              ])
	</div>
</div>
@include('partials.formFields.selectFormGroup', [
    'required' => true,
    'name' => 'user_id',
    'label' => 'USER',
    'keyValue' => true,
    'options' => $page == 'detail' ? [] : $users,
    'placeholder' => $page == 'detail' && $stable->user ? $stable->user->firstname . ' ' . $stable->user->lastname : 'Select a User',
    'disabled' => $page == 'detail' ? true : false,
    'value' => $stable->user_id ?? '',
])
