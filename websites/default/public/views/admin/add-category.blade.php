@extends('layouts.adminlayout')

@section('content')
<div class="jumbotron text-center">
  <h1 class="display-4">ADD CATEGORY</h1>
  <hr class="my-4">
  </p>
</div>

<form action="post/add-category" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="category">
    <small id="emailHelp" class="form-text text-muted">Name your category here (required).</small>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">DESCRIPTION</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description" ></textarea>
    <small id="emailHelp" class="form-text text-muted">Descripe your category (optional).</small>
  </div>
  <button type="submit" class="btn btn-primary" style="margin-left: 50%">ADD</button>
</form>

    
@endsection
