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
	<h2>Hello</h2>
	<table>
		<tr>
			<th>Product Image</th>
			<th>Product Title</th>
			<th>Quantity</th>
			<th>Total Price</th>
		</tr>
		@foreach ($carts['items'] as $cart => $values)
			<tr>
				<td><img src="@asset('views/public/image/'.$values['product_image'])"></td>
				<td>{{$values['title']}}</td>
				<td>{{$values['quantity']}}</td>
				<td>{{$values['priceTotal']}}</td>
			</tr>
    	@endforeach
	</table><br>
	<p>Total Price: <b>{{$carts['totalPrice']}}</b></p>
	<p>Total Product: <b>{{$carts['totalQty']}}</b></p>
@endsection