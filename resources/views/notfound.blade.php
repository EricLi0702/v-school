<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ mix('/css/app.css')}}">
        <link rel="stylesheet" href="./css/all.css">
        <title>v-school</title>

        <script>
            (function(){
                window.Laravel = {
                    csrfToken:'{{ csrf_token() }}'
                }
            })();
        </script>
    </head>
    <body>
        <h1 class=text-center>您没有足够的权限访问此页面</h1>
        {{-- <a href="/logoot">Logout</a> --}}
    </body>
</html>
