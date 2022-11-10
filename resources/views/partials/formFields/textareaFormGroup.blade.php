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
		  <textarea
			  name="{{$name}}"
			  class="form-control{{$errors->has($name) ? ' is-invalid' : ''}} {{isset($className) ? $className : ''}}"
			  @if (isset($idName))
			  id="{{$idName}}"
			  @endif
			  @if(isset($placeholder))
			  placeholder="{{$placeholder}}"
			  @endif
			  @if (isset($disabled) && $disabled)
			  disabled="{{$disabled}}"
			  @endif
		  >@php
         if (old($oldName)):
        echo old($oldName);
		elseif(isset($value)):
		echo $value;
		endif;
        @endphp</textarea>
        @if ($errors->has($name))
            @foreach ($errors->get($name) as $err)
				<div>
					<small class="text-danger">{{$err}}</small>
				</div>
			@endforeach
		@endif
</div>
