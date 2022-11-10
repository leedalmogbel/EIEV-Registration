@php
$start = $object->currentPage() - 3;
if ($start < 1)
    $start = 1;

$end = $object->currentPage() + 5;
if ($end > $object->lastPage())
    $end = $object->lastPage()
@endphp

<ul class="pagination">
	@if ($object->currentPage() > 1)
		<li class="page-item">
			<a href="{{$object->appends(request()->except('page'))->url($object->currentPage() - 1)}}" class="page-link prev-page">&laquo;</a>
		</li>
	@endif
	@for($page = $start; $page <= $end; $page++)
		<li class="page-item{{$page == $object->currentPage() ? ' active' : ''}}">
			<a href="{{$object->appends(request()->except('page'))->url($page)}}" class="page-link">{{$page}}</a>
		</li>
	@endfor
	@if($object->currentPage() < $object->lastPage())
		<li class="page-item">
			<a href="{{$object->appends(request()->except('page'))->url($object->currentPage() + 1)}}" class="page-link prev-page">&raquo;</a>
		</li>
	@endif
</ul>
