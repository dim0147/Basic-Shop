@extends('layouts.mainlayout')


@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/product/css/index.css')'>
@endsection

@section('content')
<div class="container">

    <div class="row">

      <div class="col-lg-3">

        <h1 class="my-4">Shop Group Project</h1>
        <div class="list-group">
          <a href="#" class="list-group-item"><i class="fas fa-fighter-jet"></i> Handgun</a>
          <a href="#" class="list-group-item"> <i class="fas fa-biohazard"></i> Shotgun</a>
          <a href="#" class="list-group-item"><i class="fas fa-bone"></i> Knife</a>
        </div>

      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
              <img class="imgFixed d-block img-fluid" src="https://trak.in/wp-content/uploads/2019/09/Flipkart-Amazon-Banner-Opt-1-1280x720-1024x576-1-1024x576.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
              <img class="imgFixed d-block img-fluid" src="https://img.mshanken.com/d/cao/bolt/2019-04/bslv19-std-800x450-2.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
              <img class="imgFixed d-block img-fluid" src="https://bigten.org/images/2019/9/11/19MBBT_Header_v2.jpg?width=1024&mode=crop" alt="Third slide">
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

        <div class="row">

        @foreach ($products as $product)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
              <a href="#"><img class="itemImage card-img-top" src="@asset('views/public/image/' . $product['image'])" alt=""></a>
              <div class="card-body">
                <h4 class="card-title">
                <a href="#">{{$product['title']}}</a>
                </h4>
                <h5>${{$product['price']}}</h5>
                <p class="card-text">{{$product['description']}}</p>
              </div>
              <div class="card-footer">
                <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
              </div>
            </div>
          </div>
        @endforeach


        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->
@endsection
