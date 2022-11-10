<table class="table table-striped table-bordered">
	<tr>
		<th width="300">Event</th>
		<th>Season</th>
        <th>Location</th>
		<th>Status</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th width="100" style="text-align:right">ACTIONS</th>
	</tr>
	@foreach ($events as $event)
	<tr>
		<td>{{$event->name}}</td>
		<td>{{$event->season->season}}</td>
		<td>
			<div>{{$event->location}}</div>
			<div><small class="text-secondary">{{$event->realCountry()}}</small></div>
		</td>
		<td>
		@include('partials.status', ['status' => $event->status])
		</td>
		<td>{{date('M d, Y', strtotime($event->start_date))}}</td>
		<td>{{date('M d, Y', strtotime($event->end_date))}}</td>
		<td style="text-align: right">
		@include('partials.actions', ['object' => $event])
		</td>
	</tr>
	@endforeach
</table>
