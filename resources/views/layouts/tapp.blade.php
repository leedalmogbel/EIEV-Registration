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
    <link type="text/css" rel="stylesheet" href="/styles/editor.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/select/1.5.0/css/select.dataTables.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css" />
    

    <link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/components/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script type="text/javascript" src="/components/toastr/build/toastr.min.js"></script>
    <script type="text/javascript" src="/components/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/components/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="/components/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.2.0/js/dataTables.dateTime.min.js"></script>
    <script type="text/javascript" src="/js/debounce.js"></script>
    <script type="text/javascript" src="/js/editor.js"></script>

	</head>
	<body>
		<div class="container-fluid">
            <div class="row no-gutters">
                @include('layouts.tsidebar')
                @yield('content')
			</div>
		</div>
		<div class="modal-overlay"></div>
        @if (Session::has('message'))
            <script type="text/javascript">
             toastr["{{ Session::has('message_type') ? Session::get('message_type') : 'info' }}"]('{{ __(Session::get('message')) }}');
            </script> @endif
        <script>
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
        </script>
 </body>
</html>
                                          
