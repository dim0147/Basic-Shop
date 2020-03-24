@extends('layouts.adminlayout')

@section('content')
<h1>EDIT {{$category['name']}} CATEGORY</h1><br>
    
<form action="post/edit-category" method="POST">
<input type="hidden" name="id" value="{{$category['id']}}">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input value="{{$category['name']}}" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="category">
    <small id="emailHelp" class="form-text text-muted">Name your category here (required).</small>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">DESCRIPTION</label>
    <textarea value="{{$category['description']}}" class="form-control" id="exampleFormControlTextarea1" rows="3" name="description" >{{$category['description']}}</textarea>
    <small id="emailHelp" class="form-text text-muted">Descripe your category (optional).</small>
  </div>
  <button type="submit" class="btn btn-primary" style="margin-left: 50%">EDIT</button>
</form>
@endsection