<div class="table-responsive col col-md col-xl col-xxl-10 align-items-center justify-content-center">
    <table id="horseListing" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th width="300">Horse</th>
                <th>Breed</th>
                <th>Country</th>
                <th>Microchip NO</th>
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
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#horseListing').DataTable();
    });
</script>
