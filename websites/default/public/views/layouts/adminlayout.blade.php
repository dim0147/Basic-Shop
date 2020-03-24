<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel="stylesheet" type="text/css" href="@asset('views/public/css/bootraps4/bootstrap.min.css')" >
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" >
    @yield('css')
</head>

<body>

@include('layouts.partials.admin_header')
@yield('content')

@include('layouts.partials.admin_footer')
<script src="@asset('views/public/js/jquery.min.js')"></script>
<script src="@asset('views/public/bootraps4/bootstrap.min.js')"></script>
@yield('javascript')
</body>
</html>