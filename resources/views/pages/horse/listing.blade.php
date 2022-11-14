<table id="horseListing" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="300">HORSE NAME</th>
            <th>BREED</th>
            <th>COUNthY</th>
            <th>MICROCHIP NO</th>
        </tr>
        {{-- <th>STATUS</th>
        <th width="100" style="text-align:right">ACTIONS</th> --}}
    </thead>
    {{-- @foreach (${Str::plural($modelName)} as $horse) --}}
    <tbody>
        @foreach ($eef_horses as $horse)
            <tr>
                <td>
                    <div>{{ $horse->name }}</div>
                    <div><small class="text-secondary">{{ $horse->horseid }}</small>
                </td>
                <td>
                    {{-- {{$horse->realBreed()}} --}}
                    {{ $horse->breed }}

                </td>
                <td>
                    {{ $horse->countryorigin }}
                </td>
                <td>
                    {{ $horse->microchip }}
                </td>
                {{-- <td>
                @include('partials.status', ['status' => $horse->status])
            </td> --}}
                {{-- <td style="text-align: right">
	        	@include('partials.actions', ['object' => $horse])
			</td> --}}
            </tr>
        @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#horseListing').DataTable();
    });
</script>
