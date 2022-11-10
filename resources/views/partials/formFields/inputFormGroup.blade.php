@php
$oldName = $name;
@endphp
@if (preg_match('/\[(.*)\]/', $name))
	@php
	$oldName = preg_replace(['/\[/', '/\]/'], ['.', ''], $name)
	@endphp
@endif
<div class="form-group mb-3">
          <label for="{{$name}}">{{$label}}{!!isset($required) && $required ? ' <span class="text-danger">*</span>' : ''!!}</label>
    <input
		@if (isset($type))
        type="{{$type}}"
		@endif
        name="{{$name}}"
        class="form-control{{$errors->has($name) ? ' is-invalid' : ''}} {{isset($className) ? $className : ''}}"
        @if (old($oldName))
        value="{{old($oldName)}}"
		@elseif(isset($value))
		value="{{$value}}"
		@endif
		@if (isset($idName))
		id="{{$idName}}"
		@endif
		@if (isset($placeholder))
        placeholder="{{$placeholder}}"
		@endif
        @if (isset($disabled) && $disabled)
		disabled="{{$disabled}}"
		@endif
        @if (isset($checked) && $checked)
		checked="checked"
		@endif
    />
    @if ($errors->has($name))
        @foreach ($errors->get($name) as $err)
			<div>
				<small class="text-danger">{{$err}}</small>
			</div>
		@endforeach
	@endif
</div>
