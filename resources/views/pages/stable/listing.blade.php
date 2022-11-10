<table class="table table-striped table-bordered">
	<tr>
		<th width="300">Stable Name</th>
		<th>Stable Type</th>
		<th>Status</th>
		<th>User</th>
		<th width="100" style="text-align:right">ACTIONS</th>
	</tr>
	@foreach ($stables as $stable)
		<tr>
			<td>{{$stable->name}}</td>
			<td>{{$stable->realType()}}</td>
			<td>@include('partials.status', ['status' => $stable->status])</td>
			<td>{{$stable->user->firstname}} {{$stable->user->lastname}}</td>
			<td style="text-align: right">@include('partials.actions', ['object' => $stable])</td>
		</tr>
	@endforeach
</table>
