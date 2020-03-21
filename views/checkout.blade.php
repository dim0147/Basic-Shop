@extends('layouts.mainlayout')

@section('content')
    <h1>Check out</h1>
    <form action="post/checkout" method="POST">
        Address: <input type="text" name="address"><br>
        Phone: <input type="text" name="phone"><br>
        Email: <input type="text" name="email"><br>
        Notice: <input type="text" name="notice"><br>
        <button type="submit" class="btn btn-primary">submit</button>
    </form>
@endsection