@extends('layouts.mainlayout')

@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/product/css/index.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')

  <div class="main">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
          <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>

      <div class="carousel-item active">
            <div class="d-block img-fluid">
              <div class="head" 
              style="background-image: url(https://trak.in/wp-content/uploads/2019/09/Flipkart-Amazon-Banner-Opt-1-1280x720-1024x576-1-1024x576.jpg)">
              </div>
              <img class="imgFixed" src="https://trak.in/wp-content/  uploads/2019/09/Flipkart-Amazon-Banner-Opt-1-1280x720-1024x576-1-1024x576.jpg" alt="First slide">
              </div>
        </div>

        <div class="carousel-item">
          <div class="d-block img-fluid">
            <div class="head" 
            style="background-image: url(https://img.mshanken.com/d/cao/bolt/2019-04/bslv19-std-800x450-2.jpg)">
              </div>
                  <img class="imgFixed" src="https://img.mshanken.com/d/cao/bolt/2019-04/bslv19-std-800x450-2.jpg" alt="Second slide">
              </div>
      </div>

        <div class="carousel-item">
            <div class="d-block img-fluid">
          <div class="head" 
          style="background-image: url(https://bigten.org/images/2019/9/11/19MBBT_Header_v2.jpg?width=1024&mode=crop)">
              </div>
                  <img class="imgFixed" src="https://bigten.org/images/2019/9/11/19MBBT_Header_v2.jpg?width=1024&mode=crop" alt="Third slide">
              </div>
        </div>
            



      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>

      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

    </div>

        <div class="body">
      <div class="parentContainer">
        <div class="header">
          <H2>Recommend for you</H2>
        </div>
        <div>
          @foreach ($products as $product)
          <a href="#" data-toggle="modal" data-target="#exampleModal" class="ch" title="{{$product['title']}}" image="views/public/image/{{$product['image']}}" des="{{$product['description']}}">
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
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </div>

    <div class="footer">
      fsdaf
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="demo"></h5>
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
            <form action="/WEBASSIGNMENT2/cart" method="POST">
            <!-- Name of input element determines name in $_FILES array -->
                Send this file: <input type="text" name="action" list="ls"/>
                <datalist id="ls">
                    <option value="add">
                    <option value="remove">
                    <option value="decrease">
                </datalist>

                <input name="id" value="4"  />
                <input name="quantity" value="1"  />
                <input type="submit" value="Send File" />
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