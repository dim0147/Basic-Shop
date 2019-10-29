@extends('layouts.mainlayout')

@section('content')
<h1>EDIT {{$category['name']}} CATEGORY</h1><br>
<form action="post/add-category" method="POST">
    <input type="hidden" name="id" value="{{$category['id']}}">
    Name: <input type="text" name="category" value="{{$category['name']}}">{{$category['name']}}<br>
    DESCRIPTION: <textarea name="description" id="" cols="30" rows="10" value="{{$category['description']}}">{{$category['description']}}</textarea><br>
    <button type="submit">Submit</button>
</form>
    
@endsection