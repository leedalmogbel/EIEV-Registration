<table class="table table-striped table-bordered">
	<tr>
		<th>Season</th>
		<th>Status</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th width="100" style="text-align:right">ACTIONS</th>
	</tr>
	@foreach ($seasons as $season)
		<tr>
			<td>{{$season->season}}</td>
			<td>@include('partials.status', ['status' => $season->status])</td>
			<td>{{date('M d, Y', strtotime($season->start_date))}}</td>
			<td>{{date('M d, Y', strtotime($season->end_date))}}</td>
			<td style="text-align: right">@include('partials.actions', ['object' => $season])</td>
		</tr>
	@endforeach
</table>
