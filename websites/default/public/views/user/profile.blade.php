@extends('layouts.mainlayout')
@section('content')
<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			border: 1px solid #dddddd;
			text-align: center;
			padding: 8px;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}

		img {
			border: 1px solid black;
			width: 120px;
			height: 120px;
		}
	</style>
<div class="row" style="margin-bottom: 10px;">
	<div class="card" style="width: 18rem;margin: 0 auto;">
	<img src="@asset('views/public/image/user-image.png')" class="card-img-top" alt="User Image">
	<div class="card-body">
		<h5 class="card-title">User Profile</h5>
		<p class="card-text">Name: {{$user['name']}}</p>
	</div>
	<ul class="list-group list-group-flush">
		<li class="list-group-item"><strong>Username:</strong> {{$user['username']}}</li>
		<li class="list-group-item"><strong>Status:</strong> {{$user['status']}}</li>
		<li class="list-group-item"><strong>Date joined:</strong> {{$user['date_create']}}</li>
	</ul>
	<div class="card-body">
		<a href="change-password" class="card-link">Change your password</a>
	</div>
	</div>
</div>
<div class="orders alert alert-success">

<div class="panel panel-default">
  <div class="panel-heading text-center">
    <h1 class="panel-title">Orders</h1>
  </div>
  <div class="panel-body">
@foreach ($orders as $orderId => $order)
<div class="card text-center">
  <div class="card-header">
    <h1>Order #{{$orderId}}</h1>
  </div>
  <div class="card-body">
        <p class="card-text"><strong>Address:</strong> {{$order['address']}}</p>
        <p class="card-text"><strong>Email:</strong> {{$order['email']}}</p>
        <p class="card-text"><strong>Status:</strong> {{$order['status']}}</p>
        <p class="card-text"><strong>PAYMENT_ID:</strong> {{$order['payment_id']}}</p>


  
    <table>
			<tr>
				<th>Product Image</th>
				<th>Product Title</th>
				<th>Quantity</th>
				<th>Total Price</th>
			</tr>
                @foreach ($order['items'] as $product)
					<tr>
						<td><img src="@asset('views/public/image/'.$product['product_image'])"></td>
						<td>{{$product['product_name']}}</td>
						<td>{{$product['product_quantity']}}</td>
						<td>${{$product['product_total_price']}}</td>
					</tr>
				@endforeach
		</table>
        <br>
            <p>Total Product: <b>{{$order['total_quantity']}}</b></p>
			<p>Total Price: <b>${{$order['total_price']}}</b></p>
  </div>
</div>
<br>
@endforeach
</div>
</div>
</div>

@endsection