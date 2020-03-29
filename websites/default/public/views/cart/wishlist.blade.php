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
	@if(isset($carts) && isset($carts['items'])  && is_array($carts['items']) && count($carts['items']) >= 1 )
		<table>
			<tr>
				<th>Product Image</th>
				<th>Product Title</th>
				<th>Quantity</th>
				<th>Total Price</th>
				<th>Action</th>
				<th>Remove</th>
			</tr>
				@foreach ($carts['items'] as $cart => $values)
					<tr>
						<td><img src="@asset('views/public/image/'.$values['product_image'])"></td>
						<td>{{$values['title']}}</td>
						<td>{{$values['quantity']}}</td>
						<td>${{$values['priceTotal']}}</td>
						<td>
							<button id-product="{{$values['product_id']}}" type="button" class="add-product btn btn-success btn-sm">+</button>
							<input type="number" name="quantity" value="1" style="width: 70px;">
							<button id-product="{{$values['product_id']}}" type="button" class="decrease-product btn btn-warning btn-sm">-</button>
						</td>
						<td><button type="button" class="delete-product btn btn-danger btn-sm" id-product="{{$values['product_id']}}">X</button></td>
					</tr>
				@endforeach
		</table><br>

		<div class="border border-dark" style="padding: 20px;">
		@if(isset($carts) && isset($carts['totalPrice']))
			<p>Total Price: <b>${{$carts['totalPrice']}}</b></p>
		@endif

		@if(isset($carts) && isset($carts['totalQty']))
			<p>Total Product: <b>{{$carts['totalQty']}}</b></p>
		@endif
		<a href="checkout" type="button" class="btn btn-outline-danger"> >>Proceed to checkout</a>
		</div>
	@else
		<h1>You currently don't have any items!!</h1>
	@endif
@endsection

@section('javascript')
<script src="@asset('views/cart/js/wishlist.js')"></script>
@endsection
