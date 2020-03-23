@extends('layouts.mainlayout')
@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/user_login.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<!--<form action="post/login" method="POST">
    user name <input type="text" name='username'>
    password <input type="text" name='password'><br>
    <button type="submit">submit</button>
</form>-->

<!DOCTYPE html>
<html>
<head>
	<title>USER REGISTRATION</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
	<h2>REGISTRATION</h2>

</div>
<form method="post" action="post/register">
	<div class="input-group">
		<label>Username</label>
		<input type="text" name="username">
	</div>

	<div class="input-group">
		<label>Password</label>
		<input type="Password" name="password">
    </div>

    <div class="input-group">
		<label>Your name</label>
		<input type="text" name="name">
	</div>

	<div class="input-group">
		<button type="submit" class="btn">Register</button>
	
	</div>
	<p>
		Already a member? <a href="login">Login</a>
	</p>
</form>
</body>
</html>

@endsection