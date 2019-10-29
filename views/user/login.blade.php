@extends('layouts.mainlayout')

@section('content')
    <form action="post/login" method="POST">
    user name <input type="text" name='username'>
    password <input type="text" name='password'><br>
    <button type="submit">submit</button>
</form>    
@endsection