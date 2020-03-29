@extends('layouts.adminlayout')
@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/user_login.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<div class="header">
	<h2>ADMIN LOGIN</h2>

</div>
<form method="post" action="/admin/post/login">
	<div class="input-group">
		<label>Username</label>
		<input type="text" name="username">
	</div>
	<div class="input-group">
		<label>Password</label>
		<input type="Password" name="password">
	</div>

	<div class="input-group">
		<button type="submit" name="login.php" class="btn">Login</button>
	
	</div>
	<p>
		Not yet an admin? <a href="/admin/register">Sign up</a>
	</p>
</form>
@endsection
