<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/bootstrap.min.css')'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" >
    @yield('css')
</head>

<body>

@include('layouts.partials.header')
@yield('content')

@include('layouts.partials.footer')
<script src="@asset('views/public/js/jquery.min.js')"></script>
<script src="@asset('views/public/js/bootstrap.min.js')"></script>
<script src="@asset('views/public/js/bootstrap.bundle.min.js')"></script>
@yield('javascript')
</body>
</html>