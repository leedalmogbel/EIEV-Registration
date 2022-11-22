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

            <div class="row">
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'fname',
                        'label' => 'FIRST NAME',
                        'value' => $profile->fname,
                        'disabled' => true,
                    ])
                </div>
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'lname',
                        'label' => 'LAST NAME',
                        'value' => $profile->lname,
                        'disabled' => true,
                    ])
                </div>
            </div>

            <div class="row">
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'mobileno',
                        'label' => 'Mobile Number',
                        'value' => $profile->mobileno,
                        'disabled' => true,
                    ])
                </div>
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'bday',
                        'label' => 'Birthdate',
                        'value' => date('d-m-Y', strtotime($profile->bday)),
                        'disabled' => true,
                    ])
                </div>
            </div>

            <div class="row">
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'stableid',
                        'label' => 'Stable ID',
                        'value' => $profile->stableid,
                        'disabled' => true,
                    ])
                </div>
                <div class="col">
                    @include('partials.formFields.inputFormGroup', [
                        'type' => 'text',
                        'required' => true,
                        'name' => 'stablename',
                        'label' => 'Stable Name',
                        'value' => $profile->stablename,
                        'disabled' => true,
                    ])
                </div>
            </div>
            <div class="visible-print text-center">
                <p>Click me to download</p>
                <a href="{{ action('UserController@downloadQRCode') }}">
                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$profile->uniqueid&choe=UTF-8"
                        alt="">
                </a>
            </div>
        </div>
    </div>
@endsection
