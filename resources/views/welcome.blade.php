<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head lang="cn">
        <meta charset="utf-8">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
        <link rel="stylesheet" href="{{ mix('/css/app.css')}}">
        <link rel="stylesheet" href="{{ asset('css/all.css')}}">
        <script src="{{ asset('/js/jquery.js')}}"></script>
        <title>E-school</title>
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" sizes="16x16">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script>
            (function(){
                window.Laravel = {
                    csrfToken:'{{ csrf_token() }}',
                    base_url:"{{URL::to('/')}}"
                }
            })();
        </script>
        <style>
            body.overflow{
                overflow: auto;
            }
        </style>
        <!-- <script src="https://118.31.71.41/external_api.js" defer></script> -->
        
    </head>
    <body>
        <div id="app">

            @if(Auth::check())
                <mainapp-component :user="{{Auth::user()}}" :member="{{$member}}" :role="{{Auth::user()->role}}"></mainapp-component>
            @else
                <mainapp-component :user="false"></mainapp-component>
            @endif
        </div>
        <script src="{{ mix('/js/app.js')}}"></script>
        {{-- <script src="{{ asset('/js/jquery.js')}}"></script> --}}
        <script>
            var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            if(width < 600){
                $('body').addClass("overflow");
            }
        </script>
    </body>
</html>
