<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel="icon" href="@asset('views/public/image/favicon.png')">
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/bootstrap.min.css')'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('css')
</head>

<body>

@include('layouts.partials.header')
@yield('content')

@include('layouts.partials.footer')
<script src="@asset('views/public/js/jquery.min.js')"></script>
<script src="@asset('views/public/js/bootstrap.min.js')"></script>
<script src="@asset('views/public/js/bootstrap.bundle.min.js')"></script>
<script src="@asset('views/public/js/searchBar.js')"></script>
@yield('javascript')
</body>
</html>