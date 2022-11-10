
<table class="table table-striped table-bordered">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Visa</th>
		<th>Status</th>
		<th width="100" style="text-align:right">ACTIONS</th>
	</tr>
	@foreach ($owners as $owner)
		<tr>
			<td>{{$owner->firstname}} {{$owner->lastname}}</td>
			<td>{{$owner->email}}</td>
			<td>{{$owner->realVisaType()}}</td>
			<td>@include('partials.status', ['status' => $owner->status])</td>
			<td style="text-align: right">@include('partials.actions', ['object' => $owner])</td>
		</tr>
	@endforeach
</table>
