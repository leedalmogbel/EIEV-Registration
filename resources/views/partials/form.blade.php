@extends('partials.frame')

@section('content')
    <div class="content col col-md">
        <div class="container">
            <div class="float-end">
                @if ($modelName === 'entry')
                    <a href="/race" class="btn btn-secondary">&lt; Back to Listing</a>
                @else
                    <a href="/{{ $modelName }}" class="btn btn-secondary">&lt; Back to Listing</a>
                @endif
            </div>
            <div class="row g-0 mx-0">
                <h1>{{ ucwords($page) }} {{ ucwords($modelName) }}</h1>
                @if ($$modelName->status)
                    <small class="text-secondary">STATUS :</small> @include('partials.status', ['status' => $$modelName->status])
                @endif
                <hr />
                <br />
                <form method="post" action="{{ $form_url ?? '' }}">
                    @csrf
                    @include("pages.$modelName.form")
                    <br /><br />
                    @if ($page == 'detail')
                    @else
                        {{-- <button type="submit" class="btn btn-main submit-btn">{{ strtoupper($page) }}</button> --}}
                        <button type="submit" class="btn btn-main submit-btn">SUBMIT</button>
                    @endif
                </form>
                <br /><br />
            </div>

        </div>
    </div>
    @yield('custom-script')
@endsection
