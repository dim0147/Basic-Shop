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
        <div class="rating text-center">
                @for ($i = 0; $i < $product['rate']; $i++)
                <span class="fa fa-star checked"></span>
                @endfor
                @for($i = $product['rate']; $i < 5; $i++)
                <span class="fa fa-star"></span>
                @endfor
                
              </div>

        <div class="panel panel-default">
            <div class="panel-body text-center">
                <img src="@asset('views/public/image/'.$product['image'])" alt="..."
                    style="width: 475px; height: 380px;margin:5px;">
            </div>
        </div>
        
        <div class="alert alert-dark text-center" style="margin: 10px;">
        <h1 class="text-center">Thumbnail</h1>
        @if(!empty($product['image_list']))
            @foreach($product['image_list'] as $img)
                <img src="@asset('views/public/image/'.$img)" alt="..." style="width:232px; height: 180px;margin:5px;">
            @endforeach
        </div>
        @else
            <h1>This product don't have any thumbnail</h1>
        @endif
        </div>
        <br>

        
        <div class="alert alert-light" role="alert">
        <h1 class="text-center">Description</h1>
            <p>{{$product['description']}}</p>
        </div>
        <div class="alert alert-primary" role="alert">
        <h4 class="price text-center">Current price: <span>${{$product['price']}}</span></h4>
        </div>

        <div class="alert alert-warning" role="alert">
        <form action="/cart/action" method="POST" style="" class="text-center">
            <input type="hidden" value="{{$product['id']}}" name="id">
            <input type="hidden" value="add" name="action">
            <input type="number" name="quantity" value="1" style="width: 70px; margin-bottom: 15px;">
            <br>
            <button type="submit" class="btn btn-success"> + Add To Cart</button>

        </form>
        </div>

    </div>
</div>
@endsection