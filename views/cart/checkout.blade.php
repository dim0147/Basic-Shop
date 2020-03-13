@extends('layouts.mainlayout')

@section('css')

<link rel="stylesheet" type="text/css" href="@asset('views/public/css/checkout.css')">

@endsection

@section('content')

<div class="wrapper">
    <div class="container">
        <form action="">
            <h1>
                Checkout
            </h1>
            <div class="name">
                <div>
                    <label for="f-name">First: </label>
                    <input type="text" name="f-name">
                </div>
                <div>
                    <label for="l-name">Last: </label>
                    <input type="text" name="l-name">
                </div>
            </div>
            <div class="street">
                <label for="name">Notice: </label>
                <input type="text" name="address">
            </div>
            <div class="address-info">
                <div>
                    <label for="city">Address: </label>
                    <input type="text" name="city">
                </div>
                <div>
                    <label for="state">Phone: </label>
                    <input type="text" name="state">
                </div>
                <div>
                    <label for="zip">Email: </label>
                    <input type="text" name="zip">
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
           