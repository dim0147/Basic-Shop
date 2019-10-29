@extends('layouts.mainlayout')

@section('content')
<h1>ADD CATEGORY</h1><br>
<form action="post/add-category" method="POST">
    Name: <input type="text" name="category"><br>
    DESCRIPTION: <textarea name="description" id="" cols="30" rows="10"></textarea><br>
    <button type="submit">Submit</button>
</form>
    
@endsection