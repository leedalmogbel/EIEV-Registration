@extends('partials.frame')
@section('content')
	<div class="content">
		<div class="btn-toolbar mb-3 float-end" role="toolbar" aria-label="Toolbar with button groups">
			<div class="btn-group mr-2" role="group" aria-label="First group">
				<a href="/{{$modelName}}/create" class="btn btn-main create-btn">
					<i class="fa fa-plus"></i> Create {{ucwords($modelName)}}
				</a>
			</div>
			@if (isset($isSearchable) && $isSearchable)
				<div class="input-group ms-2">
                    <div class="input-group-text" id="btnGroupAddon">
                        <i class="fa fa-search"></i>
					</div>
					<input
						type="text"
						class="form-control"
						placeholder="Search..."
						aria-label="Input group example"
						id="search-input"
						aria-describedby="btnGroupAddon"
						value="{{$_GET['q'] ?? ''}}"
					/>
					<a href="#" class="btn bg-transparent clear-search" style="margin-left: -35px; z-index: 100;">
						<i class="fa fa-times"></i>
					</a>
				</div>
			@endif
		</div>
		<h1>{{ucwords(\Str::plural($modelName))}}</h1>
		<br />
		<div class="listing-content">
    		@include("pages.$modelName.listing")
			<br />
	    	@include('partials.pagination', ['object' => ${Str::plural($modelName)}])
		</div>
		<script>
		 $('.approve-action').click(function (e) {
			 e.preventDefault();
			 let self = this;
			 $.confirm({
				 title: 'Approve {{ucwords($modelName)}}',
				 type: 'green',
				 content: 'Are you sure you want to approve this {{$modelName}}?',
				 buttons: {
					 Approve : {
						 btnClass: 'btn-success',
						 action: function () {
							 window.location.href = $(self).data('url')
						 }
					 },
					 Cancel : function () {

					 }
				 }
			 });
		 });
		 
		 $('.reject-action').click(function (e) {
			 e.preventDefault();
			 let self = this;
			 $.confirm({
				 title: 'Reject {{ucwords($modelName)}}',
				 type: 'red',
				 content: 'Are you sure you want to reject this {{$modelName}}?',
				 buttons: {
					 Reject : {
						 btnClass: 'btn-danger',
						 action: function () {
							 window.location.href = $(self).data('url')
						 }
					 },
					 Cancel : function () {

					 }
				 }
			 });
		 });

		 const processResponse =  (res) =>  {
			 let listingContent = $(res).find('.listing-content').html();
			 $('.listing-content').html(listingContent);
			 overlayPage(false);
		 }
		 
		 $('#search-input').change(function() {
			 let params = {};
			 params.q = $(this).val();
			 overlayPage(true);
			 pushUrl(params, processResponse);
		 });

		 $('.clear-search').click((e) => {
			 e.preventDefault();
			 $('#search-input').val('').change().focus();
		 });
		 
		</script>
		@yield('custom-script')
	</div>
@endsection
