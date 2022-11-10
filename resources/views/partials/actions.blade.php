@php
$actions = isset($actions) && is_array($actions) ? $actions : [];
@endphp
<div class="dropdown dropleft ">
    <a href="#" class="action-drop dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
		
	</a>
    <div class="dropdown-menu">
        @foreach ($actions as $name => $action)
			<a 
				@foreach($action as $attr => $attrValue)
				    @if ($attr == 'class')
  				        @php
      				        $class = $attrValue;
				        @endphp
                            
                        @continue
				    @endif
                            
                    {{$attr}}="{{$attrValue}}"
				@endforeach
				
				class="dropdown-item {{isset($class) ? $class : ''}}"
			>{!!$name!!}</a>
		@endforeach
		@if ($object->status != 'A')
        <a class="dropdown-item approve-action" href="#" data-url="/{{$modelName}}/statusUpdate/A/{{$object->getKey()}}">
            <i class="fa-solid fa-check text-success"></i>  Approve
        </a>
		@endif
		@if($object->status != 'R')
        <a class="dropdown-item reject-action" href="#" data-url="/{{$modelName}}/statusUpdate/R/{{$object->getKey()}}">
            <i class="fa fa-times text-danger"></i> Reject
        </a>
		@endif
        <a class="dropdown-item" href="/{{$modelName}}/detail/{{$object->getKey()}}"><i class="fa fa-eye text-primary"></i> View</a>
        <a class="dropdown-item" href="/{{$modelName}}/update/{{$object->getKey()}}"><i class="fa fa-pencil text-warning"></i> Update</a>
    </div>
</div>
