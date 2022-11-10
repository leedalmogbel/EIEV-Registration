<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
	<head>
		<title>{{ucwords(Str::plural($modelName))}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/font-awesome/css/font-awesome.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/@fortawesome/fontawesome-free/css/all.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/toastr/build/toastr.css" />
        <link type="text/css" rel="stylesheet" href="/components/jquery-confirm/dist/jquery-confirm.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/pace-js/themes/green/pace-theme-material.css" />
        <link type="text/css" rel="stylesheet" href="/components/daterangepicker/daterangepicker.css" />
        <link type="text/css" rel="stylesheet" href="/styles/main.css" />
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
        <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="/components/toastr/build/toastr.min.js"></script>
        <script type="text/javascript" src="/components/jquery-confirm/dist/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="/components/daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="/components/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	</head>
	<body>
		<div class="wrapper row">
			<div class="sidebar col-sm">
				<div class="logo"><img src="/assets/images/dash-logo.png" /></div>
				<ul class="side-menu">
                    <li>
						<a href="#" class="{{!$modelName ? 'active' : ''}}">
							<i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
						</a>
					</li>
					<li>
						<a href="#">
							<i class="fa fa-users" aria-hidden="true"></i> Users
						</a>
					</li>
					<li>
						<a href="#">
							<i class="fa fa-calendar" aria-hidden="true"></i> Calendar
						</a>
					</li>
					<li>
						<a href="/race" class="{{$modelName == 'race' ? 'active' : ''}}">
							<i class="fa fa-flag-checkered" aria-hidden="true"></i> Races
						</a>
					</li>
					<li>
						<a href="#">
							<i class="fa fa-list-alt" aria-hidden="true"></i> Entries
						</a>
					</li>
					<li>
						<a href="/horse" class="{{$modelName == 'horse' ? 'active' : ''}}">
							<i class="fa fa-horse" aria-hidden="true"></i> Horses
						</a>
					</li>
					<li>
						<a href="/rider" class="{{$modelName == 'rider' ? 'active' : ''}}">
						    <i class="fa-solid fa-hat-cowboy"></i> Riders
						</a>
					</li>
					<li>
						<a href="/trainer" class="{{$modelName == 'trainer' ? 'active' : ''}}">
							<i class="fa fa-paw" aria-hidden="true"></i> Trainers
						</a>
					</li>
					<li>
						<a href="/season" class="{{$modelName == 'season' ? 'active' : ''}}">
							<i class="fa fa-cloud" aria-hidden="true"></i> Seasons
						</a>
					</li>
					<li>
						<a href="/owner" class="{{$modelName == 'owner' ? 'active' : ''}}">
							<i class="fa fa-user" aria-hidden="true"></i> Owners
						</a>
					</li>
					<li>
						<a href="/event" class="{{$modelName == 'event' ? 'active' : ''}}">
							<i class="fa fa-list" aria-hidden="true"></i> Events
						</a>
					</li>
					<li>
						<a href="/stable" class="{{$modelName == 'stable' ? 'active' : ''}}">
							<i class="fa fa-home" aria-hidden="true"></i> Stables
						</a>
					</li>
				</ul>
			</div>
			<div class="main-content col-sm">
				@yield('content')
			</div>
		</div>
		<div class="modal-overlay"></div>
        @if (Session::has('message'))
            <script type="text/javascript">
             toastr["{{ Session::has('message_type') ? Session::get('message_type') : 'info'}}"]('{{ __(Session::get("message")) }}');
            </script>
        @endif
        <script>
		 $('.sidebar').height($(document).height());

		 const overlayPage = (show) => {
			 if (show) {
				 $('body').addClass('modal-show');
				 return;
			 }
			 
			 $('body').removeClass('modal-show');
			 return;
		 };

		 const loadContent = (url, callback) => {
			 $.get(
				 url,
				 callback
			 ).fail((data) => {
				 overlayPage(false);
				 $.alert({
					 title : 'Error',
					 content : 'An error occured. Please try again later.'
				 });
			 });
		 }
		 
		 const pushUrl = (params, callback) => {
             let urlParams = [];
             
             for(let param in params) {
                 urlParams.push(`${param}=${params[param]}`);
             }

			 urlParams = urlParams.join('&');

			 let url = '/{{$modelName}}';

			 url += `?${urlParams}`;
			 
			 window.history.pushState(url, 'Title', url);
			 loadContent(url, callback);
		 };
		 
		 window.onpopstate = (event) => {
			 window.location.href = document.location;
		 };
		</script>
	</body>
</html>
                                          
