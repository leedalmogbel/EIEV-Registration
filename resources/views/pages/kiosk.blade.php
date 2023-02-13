@extends('partials.frame')

@section('content')
    <div class="content col col-md-9">
        <div class="container">
            <div class="d-flex justify-content-between">
                <h1>{{ ucwords($modelName) }}</h1>
                <a href="/dashboard" class="btn btn-secondary">&lt; Back to Dashboard</a>
            </div>
            <hr />
            <br />

            <div class="visible-print text-center">
                <div class="row">
                    <div class="col-12">
                        <p class="h1">SCAN @ KIOSK OR DOWNLOAD QR CODE</p>
                        <a href="{{ action('UserController@downloadQRCode') }}">
                            <img src="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl={{ $profile->uniqueid }}&choe=UTF-8"
                                alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="{{ action('UserController@downloadQRCode') }}" class="btn btn-main">
                            DOWNLOAD QRCODE
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
