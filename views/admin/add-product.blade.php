@extends('layouts.mainlayout')

@section('content')
<form action="/WEBASSIGNMENT2/cart" method="POST">
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="action" value="add"  />
     <input name="id" value="4"  />
      <input name="quantity" value="1"  />
    <input type="submit" value="Send File" />
</form>
@endsection