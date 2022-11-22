@extends('layouts.tapp')
@section('content')
<div class="d-grid gap-2 mt-2">
    
    <div class="input-group input-group-lg input-toggle" id="input-search">
        <input type="text" id="search" class="form-control text-center" autofocus>
        
    </div>
    @if($profile)
    <div class="row">
        <div class="col row">
            <div class="col">
                <div class="col">{{$profile->fname . " ". $profile->lname}}</div>
                <div class="col">{{$profile->email}}</div>
                <div class="col">{{$profile->userid}}</div>
                <div class="col">{{$profile->stableid}}</div>
            </div>

            <div class="col">{{QrCode::style($request->style??'square')->encoding('UTF-8')->size($request->size ?? 200)->generate($profile->uniqueid)}}</div>
        </div>
        <div class="col row">
            <div class="col">{{QrCode::style($request->style??'square')->encoding('UTF-8')->size($request->size ?? 200)->generate($profile->uniqueid)}}</div>
            <div class="col">{{QrCode::style($request->style??'square')->encoding('UTF-8')->size($request->size ?? 200)->generate($profile->uniqueid)}}</div>
        </div>
    </div>
    @endif
    @if(count($actions)>0)
    <div class="d-grid gap-2">
        @foreach($actions as $key => $value)
        <button class="btn btn-primary" type="button"id={{$key}}>{{$value}}</button>
        @endforeach
    </div>
    @endif
</div>
<style>
    .input-toggle{
        opacity:0;
        margin-top:-5rem;
        transition: 1s;
    }
</style>
<script>
    $(document).ready(function(e) {
        let keyLog = {}
        const handleKeyboard = ({ type, key, repeat, metaKey }) => {
            if (repeat) return
            if (type === 'keydown') {
                keyLog[key] = true
                if (key === '`'){
                    $("#input-search").toggleClass('input-toggle');
                    searchfield.focus();
                }
                if (key.enter){
                    keyLog = {};
                }
            }
        }
        document.addEventListener('keydown', handleKeyboard)
        let searchfield = $('#search').on('blur',function(e){
            setTimeout(function () {
                searchfield.focus();
            },10);
        });
        $('#search').on('keypress', function (event) {
            var regex = new RegExp("[^`]");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                searchfield.focus();
                event.preventDefault();
                return false;
            }
        });
        $('#a').on('click',()=>{alert($('#search').val())});
    });
</script>
@endsection