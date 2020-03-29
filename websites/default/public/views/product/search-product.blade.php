@extends('layouts.mainlayout')

@section('css')
  <link rel="stylesheet" type="text/css" href='@asset('views/public/css/productIndex.css')'>
@endsection

@section('content')
  <meta name="viewport" content="width=1177"/>
  <div class="main">

        <div class="body">
      <div class="parentContainer">
        <div class="header">
          <H2>Search By {{$searchKey}}</H2>
        </div>
        <div>
        @if(!empty($products))
          @foreach ($products as $product)
          <a href="#" data-toggle="modal" data-target="#exampleModal" class="ch" identify="{{$product['id']}}" title="{{$product['title']}}" 
          image="/views/public/image/{{$product['image']}}" des="{{$product['description']}}">
            <img class="im" src="@asset('views/public/image/'.$product['image'])" alt="">
            <div class="product">
              <div class="title">
                <b>{{$product['title']}}</b>
              </div>
              <div class="price">
                <h3>${{$product['price']}}</h3>
              </div>
              <div class="description">
                <p>{{$product['description']}}</p>
              </div>
              <div class="rating">
              @for ($i = 0; $i < $product['rate']; $i++)
                <span class="fa fa-star checked"></span>
                @endfor
                @for($i = $product['rate']; $i < 5; $i++)
                <span class="fa fa-star"></span>
                @endfor
              </div>
            </div>
          </a>
          @endforeach
          @else
            <h1>No result found!</h1>
          @endif
        </div>
      </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">x</span>
            </button>
          </div>
          <div class="modal-body">
            <img id="modalImage" alt="blk">
            <div class="modalContainer">
                <h5>DESCRIPTION</h5>
                <div id="modalDescription"></div>
            </div>
            <!--<p id="das" class="modalImage" alt="blk"></p>-->
          </div>
          <div class="modal-footer">
            <form class="form" action="/cart/action" method="POST">
              <div>
                <input type="hidden" name="id" id="modalID">
              </div>

              <div>
                Action:
                <select name="action" list="ls">
                  <option value="add">Add</option>
                  <option value="remove">Remove</option>
                  <option value="decrease">Decrease</option>
                </select>
              </div>

              <div>
                Quantity:
                <input type="number" name="quantity" value="1"/>
              </div>

              <div>
              <a href="" type="button" class="btn btn-danger show-dt">Show Detail</a>
                <button type="submit" class="btn btn-info">SUBMIT</button>
              </div>
            </form>
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Cart</button>
                <button type="button" class="btn btn-primary">BUY</button>-->
          </div>
          </div>
      </div>
    </div>
  </div>
  @endsection
  @section('javascript')
  <script src="@asset('views/product/js/javascript.js')"></script>
  @endsection

