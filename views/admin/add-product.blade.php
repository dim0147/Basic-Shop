@extends('layouts.mainlayout')

@section('content')
<form enctype="multipart/form-data" action="post/add-product" method="POST">
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="header" type="file" multiple />
    <input type="submit" value="Send File" />
</form>
@endsection