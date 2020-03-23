@extends('layouts.mainlayout')

@section('content')
<style>
.product-title, .price, .sizes, .colors {
  text-transform: UPPERCASE;
  font-weight: bold; }

.checked, .price span {
  color: #ff9f1a; }

.product-title, .rating, .product-description, .price, .vote, .sizes {
  margin-bottom: 15px; }

</style>
<div class="jumbotron">
    <div class="container">
        <h1 class="text-center">{{$product['title']}}</h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <img src="@asset('views/public/image/'.$product['image'])" alt="..."
                    style="width: 100%; height: 680px;margin:5px;">
            </div>
        </div>
        <h1 class="text-center">Thumbnail</h1>
        <div class="row">
            @foreach($product['image_list'] as $img)
            <div class="col-xs-6 col-md-3">
                <img src="@asset('views/public/image/'.$img)" alt="..." style="width: 100%; height: 180px;margin:5px;">
            </div>
            @endforeach
        </div>
        <br>

        <h4 class="price text-center">Current price: <span>${{$product['price']}}</span></h4>

        <div class="jumbotron">
        <h1 class="text-center">Description</h1>
            <p>{{$product['description']}}</p>
        </div>

        <form action="/cart/action" method="POST" style="padding-left: 40%;">
            <input type="hidden" value="{{$product['id']}}" name="id">
            <input type="hidden" value="add" name="action">
            <span>Quantity: </span>
            <input type="number" name="quantity" value="1" style="width: 50px; margin-bottom: 15px; margin-left: 5px;">
            <br>
            <button type="submit" class="btn btn-success"> + Add To Cart</button>

        </form>

    </div>
</div>
@endsection