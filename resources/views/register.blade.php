<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
	<head>
		<title>Register</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/font-awesome/css/font-awesome.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/toastr/build/toastr.css" />
        <link type="text/css" rel="stylesheet" href="/components/jquery-confirm/dist/jquery-confirm.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/pace-js/themes/green/pace-theme-material.css" />
        <link type="text/css" rel="stylesheet" href="/components/daterangepicker/daterangepicker.css" />
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
        <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="/components/toastr/build/toastr.min.js"></script>
        <script type="text/javascript" src="/components/jquery-confirm/dist/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="/components/daterangepicker/daterangepicker.js"></script>
		<style>
		 html {
			 font-size: 14px;
			 font-family: Roboto 'sans-serif';
		 }

		 a { color: #A49467; }
		 a:hover { color: #A49467; }
		 
		 .login-wrapper { width: 99%; }
		 .sidebar {
			 background: url('/assets/images/bg-sidebar.png');
			 height: 100vh;
			 max-width: 460px;
		 }

		 .sidebar .login-logo {
			 display: block;
			 margin: auto;
			 margin-top: 30%;
			 width: 50%;
		 }

		 .main-div {
			 background: url('/assets/images/bg-pattern.png');
		 }

		 .login-form {
			 margin-top: 10%;
		 }

		 .login-form div.form {
			 border-right: 1px solid #A49467;
		 }

		 h1 {
			 font-size: 21px;
			 font-weight: bold;
		 }

		 h2 {
			 font-size: 19px;
			 font-weight: bold;
		 }

		 form label {
			 font-size: 12px;
			 font-weight: bold;
		 }

		 form input[type="text"], form input[type="password"], form input[type="email"] {
			 font-size: 14px;
			 font-family: Roboto 'sans-serif';
			 height: 40px;
			 padding: 15px;
		 }

		 select, input[type="file"] {
			 height: 35px !important;
		 }

		 .btn-main {
			 background: #A49467;
			 border-color: #A49467;
			 color: #fff;
			 font-size: 12px;
			 padding: 10px 20px;
		 }

		 .connect {
			 padding: 0 20px;
		 }

		 .connect .connect-link {
			 margin-top: 30px;
		 }

		 .connect .connect-link a {
			 display: inline-block;
			 background: #A49467;
			 color: #fff;
			 padding: 7px 15px;
			 
		 }

		 .doc-table {
			 background: #f1f1f1;
		 }

		 .doc-table, .doc-table tr, .doc-table td {
			 border: none;
		 }

		 .doc-table td {
			 display: table-cell;
			 vertical-align: middle;
		 }

		 h3 {
			 font-size: 16px;
			 font-weight: normal;
		 }
		</style>
    </head>
    <body>
		<div class="login-wrapper">
			<div class="row">
				<div class="col-md sidebar">
					<img src="/assets/images/eiv-logo.png" class="login-logo" />
				</div>
				<div class="col-lg main-div">
					<form method="post" enctype="multipart/form-data">
						<div class="login-form">
							<h1>Create an account</h1>
							@csrf
							<div class="row my-4">
								<div class="col-8">
									<h2 class="text-danger">Personal Details</h2>
									<div class="row my-3">
										<div class="col">
                                            @include('partials.formFields.inputFormGroup',
                                                     ['name' => 'firstname',
                                                      'label' => 'FIRST NAME',
                                                      'placeholder' => 'Enter your firstname',
                                                      'type' => 'text',
                                                      'required' => true
                                                     ])
										</div>
										<div class="col">
                                            @include('partials.formFields.inputFormGroup',
                                                     ['name' => 'lastname',
                                                      'label' => 'LAST NAME',
                                                      'placeholder' => 'Enter your lastname',
                                                      'type' => 'text',
                                                      'required' => true
                                                     ])
										</div>
									</div>
									<div class="row">
										<div class="col">
                                            @include('partials.formFields.inputFormGroup',
                                                     ['name' => 'email',
                                                      'label' => 'EMAIL ADDRESS',
                                                      'placeholder' => 'Enter your email address',
                                                      'type' => 'email',
                                                      'required' => true
                                                     ])
										</div>
										<div class="col">
                                            @include('partials.formFields.inputFormGroup',
                                                     ['name' => 'dob',
                                                      'label' => 'DATE OF BIRTH',
                                                      'type' => 'text',
                                                      'idName' => 'datepicker',
                                                      'required' => true
                                                     ])
										</div>
									</div>
									@include('partials.formFields.inputFormGroup',
                                             ['name' => 'phone',
                                              'label' => 'MOBILE/PHONE  NO.',
                                              'type' => 'text',
                                              'placeholder' => 'Enter your mobile/phone no.',
                                              'required' => true
                                             ])
									
									@include('partials.formFields.selectFormGroup',
                                             ['name' => 'location',
                                              'label' => 'LOCATION',
                                              'placeholder' => 'Enter your mobile/phone no.',
                                              'required' => true,
                                              'options' => $locations
                                             ])
									
									@include('partials.formFields.selectFormGroup',
                                             ['name' => 'discipline',
                                              'label' => 'DISCIPLINE',
                                              'required' => true,
                                              'keyValue' => true,
                                              'options' => $disciplines
                                             ])
									@include('partials.formFields.inputFormGroup',
                                             ['name' => 'emirates_id',
                                              'label' => 'EMIRATES ID',
                                              'type' => 'text',
                                              'placeholder' => 'Enter your Emirates ID',
                                              'required' => true
                                             ])
								</div>
								<div class="col">
									<h2 class="text-danger">Login Details</h2>
									@include('partials.formFields.inputFormGroup',
                                             ['name' => 'username',
                                              'label' => 'USERNAME',
                                              'type' => 'text',
                                              'placeholder' => 'Enter your desired username',
                                              'required' => true
                                             ])
									
									@include('partials.formFields.inputFormGroup',
                                             ['name' => 'password',
                                              'label' => 'PASSWORD',
                                              'type' => 'password',
                                              'placeholder' => 'Enter desired password',
                                              'required' => true
                                             ])
									
									@include('partials.formFields.inputFormGroup',
                                             ['name' => 'c_pass',
                                              'label' => 'CONFIRM PASSWORD',
                                              'type' => 'password',
                                              'placeholder' => 'COnfirm password',
                                              'required' => true
                                             ])
								</div>
							</div>
							
							<h2 class="text-danger">Documents Verification</h2>
							<table class="table doc-table">
								<tr>
									<td>
										<h3>NATIONAL ID</h3>
									</td>
									<td>
                                        @include('partials.formFields.inputFormGroup',
                                             ['name' => 'document_expiry',
                                              'label' => 'DOCUMENT EXPIRY',
                                              'type' => 'text',
										      'idName' => 'doc_exp'
                                             ])
									</td>
									<td>
									<td>
                                        @include('partials.formFields.inputFormGroup',
                                             ['name' => 'document_file',
                                              'label' => 'SELECT FILE',
                                              'type' => 'file',
                                             ])
									</td>
								</tr>
							</table>
							<button class="btn btn-main">Create</button>
							<br /><br />
						</div>
					</form>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		 $('#datepicker').daterangepicker({
			 singleDatePicker: true,
			 startDate: moment().subtract(18, 'years'),
			 locale: {
				 format: 'Y-MM-DD'
			 }
		 });
		 
		 $('#doc_exp').daterangepicker({
			 singleDatePicker: true,
			 startDate: moment().add(2, 'years'),
			 drops: 'up',
			 locale: {
				 format: 'Y-MM-DD'
			 }
		 });

		 $('.sidebar').height($(document).height());
		</script>
		
        @if (Session::has('message'))
            <script type="text/javascript">
             toastr["{{ Session::has('message_type') ? Session::get('message_type') : 'info'}}"]('{{ __(Session::get("message")) }}');
            </script>
        @endif
    </body>
</html>
