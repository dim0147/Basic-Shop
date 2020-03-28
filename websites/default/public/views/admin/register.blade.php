@extends('layouts.adminlayout')
@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/user_login.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<div class="header">
	<h2>ADMIN REGISTER</h2>

</div>
<form method="post" action="/admin/post/register">
	<div class="input-group">
		<label>Username</label>
		<input type="text" name="username">
	</div>

	<div class="input-group">
		<label>Password</label>
		<input type="Password" name="password">
	</div>

    <div class="input-group">
		<label>Your name:</label>
		<input type="text" name="name">
	</div>

	<div class="input-group">
		<button type="submit" name="login.php" class="btn">Register</button>
	
	</div>
	<p>
		Admin already? <a href="/admin/register">Login</a>
	</p>
</form>
@endsection