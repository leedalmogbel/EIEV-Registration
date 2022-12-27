<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">

<head>
    @php
        if (Request::is('dashboard')) {
            $modelName = 'dashboard';
        }
    @endphp
    <title>{{ ucwords(Str::plural($modelName)) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/components/@fortawesome/fontawesome-free/css/all.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/toastr/build/toastr.css" />
        <link type="text/css" rel="stylesheet" href="/components/jquery-confirm/dist/jquery-confirm.min.css" />
        <link type="text/css" rel="stylesheet" href="/components/pace-js/themes/green/pace-theme-material.css" />
        <link type="text/css" rel="stylesheet" href="/components/daterangepicker/daterangepicker.css" />
        <link type="text/css" rel="stylesheet" href="/styles/main.css" />
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
        <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
		<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
        <script type="text/javascript" src="/components/toastr/build/toastr.min.js"></script>
        <script type="text/javascript" src="/components/jquery-confirm/dist/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="/components/daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="/components/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	</head>
    <style>
        .loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }
        .loader {            
            /* margin: 60px auto; */
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(255, 255, 255, 0.2);
            border-right: 1.1em solid rgba(255, 255, 255, 0.2);
            border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
            border-left: 1.1em solid #ffffff;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
        }
        @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        #loadingDiv {
            position:absolute;;
            top:0;
            left:0;
            width:100%;
            min-height:100vh;
            background: rgba(19, 33, 74, 0.7);
        }
    </style>
    <style>
        span.content__hamburger, span.content__title {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
        }
        span.content__hamburger {
            background-color: #13214A;
        }

        nav.navbar.navbar-inverse.navbar-fixed-top {
            background-color: #13214A;
        }
    </style>
	<body>  
		<div class="container-fluid">
            <div class="row">
                <nav class="navbar navbar-inverse navbar-fixed-top p-2">
                    <span class="content__hamburger" id="nav-header"><i class="fa-solid fa-bars"></i></span>
                    <span class="content__title"> EIEV REGISTRATION</span>
                </nav>
            </div>
			<div class="row no-gutters">
				@include('partials.sidebar')
				@yield('content')
			</div>
		</div>
		<div class="modal-overlay"></div>
        @if (Session::has('message'))
            <script type="text/javascript">
             toastr["{{ Session::has('message_type') ? Session::get('message_type') : 'info' }}"]('{{ __(Session::get('message')) }}');
            </script> @endif
        <script>
            $('body').append(
                '<div style="" class="d-flex justify-content-center align-items-center" id="loadingDiv"><div class="loader">Loading...</div></div>'
            );
            $(window).on('load', function() {
                setTimeout(removeLoader, 500); //wait for page load PLUS two seconds.
            });

            function removeLoader() {
                $("#loadingDiv").fadeOut(500, function() {
                    // fadeOut complete. Remove the loading div
                    $("#loadingDiv").remove(); //makes page more lightweight 
                });
            }

            function showLoading() {
                document.querySelector('#loading').classList.add('loading');
                document.querySelector('#loading-content').classList.add('loading-content');
            }

            function hideLoading() {
                document.querySelector('#loading').classList.remove('loading');
                document.querySelector('#loading-content').classList.remove('loading-content');
            }


            // $('.sidebar').height($(document).height());

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
                        title: 'Error',
                        content: 'An error occured. Please try again later.'
                    });
                });
            }

            const pushUrl = (params, callback) => {
                let urlParams = [];

                for (let param in params) {
                    urlParams.push(`${param}=${params[param]}`);
                }

                urlParams = urlParams.join('&');

                let url = '/{{ $modelName }}';

                url += `?${urlParams}`;

                window.history.pushState(url, 'Title', url);
                loadContent(url, callback);
            };

            window.onpopstate = (event) => {
                window.location.href = document.location;
            };

            $(document).ready(function() {
                const sidebar = $('.sidebar')[0];
                const hamburger = $('.content__hamburger')[0];
                const closeBtn = $('.sidebar__close')[0];

                $(hamburger).on('click', function() {
                    $(sidebar).toggleClass('sidebar--show');
                    $('body').toggleClass('overflow-hidden');
                });

                $(closeBtn).on('click', function() {
                    $(sidebar).removeClass('sidebar--show');
                    $('body').removeClass('overflow-hidden');
                });

                $('#nav-header').click(function() {
                    $(this).find('i').toggleClass('fa-bars fa-xmark')
                });
            });
        </script>
 </body>
</html>
