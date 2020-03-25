@extends('layouts.mainlayout')
@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/public/css/user_login.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
@endsection
@section('content')
<!--<form action="post/login" method="POST">
    user name <input type="text" name='username'>
    password <input type="text" name='password'><br>
    <button type="submit">submit</button>
</form>-->

<div class="header">
	<h2>Change Password</h2>

</div>
<form method="post" action="post/change-password">
	<div class="input-group">
		<label>Old password</label>
		<input type="Password" name="old-password">
	</div>
	<div class="input-group">
		<label>New Password</label>
		<input type="Password" name="new-password">
	</div>

    <div class="input-group">
		<label>Confirm new Password</label>
		<input type="Password" name="confirm-new-password">
	</div>

	<div class="input-group">
		<button type="submit" class="btn">CHANGE</button>
	</div>
</form>

@endsection