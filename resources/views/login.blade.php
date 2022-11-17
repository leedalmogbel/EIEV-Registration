<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/components/@fortawesome/fontawesome-free/css/all.min.css" />
    <link type="text/css" rel="stylesheet" href="/components/toastr/build/toastr.css" />
    <link type="text/css" rel="stylesheet" href="/components/jquery-confirm/dist/jquery-confirm.min.css" />
    <link type="text/css" rel="stylesheet" href="/components/pace-js/themes/green/pace-theme-material.css" />
    <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="/components/toastr/build/toastr.min.js"></script>
    <script type="text/javascript" src="/components/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <style>
        html {
            font-size: 14px;
            font-family: Roboto 'sans-serif';
        }

        a {
            color: #A49467;
        }

        a:hover {
            color: #A49467;
        }

        .login-wrapper {
            width: 99%;
        }

        .sidebar {
            background: url('/assets/images/bg-sidebar.png');
            min-height: 100vh;
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

        /* .login-form div.form {
            border-right: 1px solid #A49467;
        } */

        h1 {
            font-size: 21px;
            font-weight: bold;
        }

        form {
            margin-top: 30px;
            padding: 20px;
        }

        form label {
            font-size: 12px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="password"] {
            font-size: 14px;
            font-family: Roboto 'sans-serif';
            height: 35px;
            padding: 15px;
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
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="row">
            <div class="col-md sidebar">
                <img src="/assets/images/eiev-logo.png" class="login-logo" />
            </div>
            <div class="col-lg main-div">
                <div class="row login-form">
                    <div class="col-7 form">
                        <h1>Login into your account</h1>
                        <form method="post" action="/login">
                            <!-- Info Alert -->
                            <div class="alert alert-info alert-dismissible d-flex align-items-center fade show justify-content-center">
                                <i class="fa-solid fa-circle-info"></i>
                                <strong class="mx-2">LOGIN WITH YOUR UAEERF REGISTERED CREDENTIALS.</strong>
                            </div>
                            @csrf
                            @include('partials.formFields.inputFormGroup', [
                                'name' => 'username',
                                'label' => 'Email Address',
                                'placeholder' => 'Enter your email address',
                                'type' => 'text',
                            ])

                            @include('partials.formFields.inputFormGroup', [
                                'name' => 'password',
                                'label' => 'PASSWORD',
                                'placeholder' => 'Enter your password',
                                'type' => 'password',
                            ])
                            <div class="py-1">
                            <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="py-3">
                                <button class="btn btn-main col-12" type="submit">LOGIN MY ACCOUNT</button>
                            </div>
							<div class="py-3 text-center">
								<a href="https://portal.uaeerf.ae/forgot_password.php" target="_blank">
									<p class=" ">FORGOT PASSWORD?</p>
								</a>
                            </div>
                        </form>
						
                    </div>
					<div class="col-5 d-flex align-items-center">
						<div class="eiev-inquiry">
							
						</div>
                        <div class="important-notice border border-3 p-2">
                            <div class="d-flex align-items-center">
                                <h4 class="" style="color: #A49467;">    
                                    <i class="fa-solid fa-circle-info" style="font-size: 20px; color: #A49467;"></i>
                                    Important Notice/s:
                                </h4>
                            </div>
                            <ul>
                                <li><p class="fs-5">if you're not getting logged in, you may need to sign in to your <strong><a href="https://portal.uaeerf.ae/">UAEERF Portal</a></strong> to update your password.</p></li>
                            </ul>
                            <h5>For any inquiries or questions with the EIEV Registration:</h5>

                            <div class="row">
                                <div class="col text-center">
							        <p class="pt-1 fs-5"><a href="tel:+971563338562"><i class="fa-solid fa-phone"></i> 0563338562</a></p>
                                </div>
                                <div class="col text-center">
                                    <p class="pt-1 fs-5"><a href="mailto:registration@eiev.ae"><i class="fa-solid fa-at"></i > registration@eiev.ae</a></p>
                                </div>
                            </div>
							
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
    @if (Session::has('message'))
        <script type="text/javascript">
            toastr["{{ Session::has('message_type') ? Session::get('message_type') : 'info' }}"](
                '{{ __(Session::get('message')) }}');
        </script> @endif
</body>

</html>
