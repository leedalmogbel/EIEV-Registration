@php
$oldName = $name;
@endphp
@if (preg_match('/\[(.*)\]/', $name))
	@php
	$oldName = preg_replace(['/\[/', '/\]/'], ['.', ''], $name)
	@endphp
@endif

@php
$value = isset($value) && $value ? $value : '';
if (old($oldName)) $value = old($oldName);
$options = isset($options) && is_array($options) ? $options : [];
@endphp
<div class="form-group mb-3">
	<label>{{$label}} {!! isset($required) && $required ? '<span class="text-danger">*</span>' : '' !!}</label>
	<select
		@if (isset($idName))
		id="{{$idName}}"
		@endif
		name="{{$name}}"
		class="form-control{{$errors->has($name) ? ' is-invalid' : ''}} {{isset($className) ? $className : ''}}"
		@if(isset($disabled) && $disabled)
		disabled="{{$disabled}}"
		@endif
	>
    @if (isset($placeholder) && $placeholder)
		<option disabled selected>{{$placeholder}}</option>
	@endif
	@foreach ($options as $key => $option)
		@if (isset($keyValue) && $keyValue)
			@if (is_numeric($value))
				@php
				$value = (int) $value;
				@endphp
			@endif
			@if (is_numeric($key))
				@php
				@endphp
			@endif
			<option{{$value === $key ? ' selected': ''}} value="{{$key}}">{{$option}}</option>
		@else
			<option{{$value == $option ? ' selected': ''}}>{{$option}}</option>
		@endif
	@endforeach
	</select>
	@if ($errors->has($name))
        @foreach ($errors->get($name) as $err)
			<div><small class="text-danger">{{$err}}</small></div>
		@endforeach
	@endif
</div>
