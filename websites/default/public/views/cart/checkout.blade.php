@extends('layouts.mainlayout')

@section('content')
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
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

    .checkoutdiv{
        margin: 0 auto;
        width:80% /* value of your choice which suits your alignment */
    }
</style>

<h1 class="text-center">Review your product</h1>
@if(isset($carts) && isset($carts['items']) && is_array($carts['items']) && count($carts['items']) >= 1 )
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
            <button id-product="{{$values['product_id']}}" type="button"
                class="add-product btn btn-success btn-sm">+</button>
            <input type="number" name="quantity" value="1" style="width: 70px;">
            <button id-product="{{$values['product_id']}}" type="button"
                class="decrease-product btn btn-warning btn-sm">-</button>
        </td>
        <td><button type="button" class="delete-product btn btn-danger btn-sm"
                id-product="{{$values['product_id']}}">X</button></td>
    </tr>
    @endforeach
</table>
<div class="" style="padding: 20px;">
    @if(isset($carts) && isset($carts['totalPrice']))
    <p>Total Price: <b>${{$carts['totalPrice']}}</b></p>
    @endif

    @if(isset($carts) && isset($carts['totalQty']))
    <p>Total Product: <b>{{$carts['totalQty']}}</b></p>
    @endif
</div>

<h1 class="text-center">CHECKOUT</h1>
<div style="margin-left:40%">
    <form class="" action="post/checkout" method="POST">
        <fieldset>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="address">Address</label>
                <div class="col-md-4">
                    <input id="address" name="address" type="text" placeholder="Your address..."
                        class="form-control input-md">
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Email</label>
                <div class="col-md-4">
                    <input id="email" name="email" type="text" placeholder="Your email..." class="form-control input-md">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="phone">Phone</label>
                <div class="col-md-4">
                    <input id="phone" name="phone" type="text" placeholder="Your phone number..."
                        class="form-control input-md">

                </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="notice">Notice</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="notice" name="notice"
                        placeholder="Your notice you want to say with dealer..."></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4">
                <button type="submit" class="btn btn-danger">CHECKOUT</button>
                </div>
            </div>
        </fieldset>
    </form>
    </div>
@else
<h1>You currently don't have any items!!</h1>
@endif
@endsection
