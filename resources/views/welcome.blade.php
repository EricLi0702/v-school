<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ mix('/css/app.css')}}">
        <link rel="stylesheet" href="./css/all.css">
        <title>E-school</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script>
            (function(){
                window.Laravel = {
                    csrfToken:'{{ csrf_token() }}'
                }
            })();
        </script>
        
    </head>
    <body>
        <div id="app">

            @if(Auth::check())
                <mainapp-component :user="{{Auth::user()}}" :permission="{{Auth::user()->role->permission}}"></mainapp-component>
            @else
                <mainapp-component :user="false"></mainapp-component>
            @endif
        </div>
        <script src="{{ mix('/js/app.js')}}"></script>
    </body>
</html>
