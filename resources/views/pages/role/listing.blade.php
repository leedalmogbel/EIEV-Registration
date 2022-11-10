<table class="table table-striped table-bordered">
	<tr>
		<th>ROLE</th>
		<th>STATUS</th>
		<th width="100" style="text-align: right">ACTIONS</th>
	</tr>
	@foreach ($roles as $role)
		<tr>
			<td>{{$role->role}}</td>
			<td>
            @if ($role->active)
				<span class="text-success">ACTIVE</span>
			@else
				<span class="text-danger">INACTIVE</span>
			@endif
			</td>
			<td style="text-align: right;">@include('partials.actions', ['object' => $role])</td>
		</tr>
	@endforeach
</table>
