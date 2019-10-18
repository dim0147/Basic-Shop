@extends('layouts.mainlayout')

@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/product/css/index.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
	<div class="main">

		<div class="head">
        	<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
        		<ol class="carousel-indicators">
        	    	<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        	    	<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        	    	<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        		</ol>

        	 	<div class="carousel-inner" role="listbox">

        	    	<div class="carousel-item active">
        	      		<img class="imgFixed d-block img-fluid" src="https://trak.in/wp-content/	uploads/2019/09/Flipkart-Amazon-Banner-Opt-1-1280x720-1024x576-1-1024x576.jpg" alt="First slide">
        	    	</div>
        	    	<div class="carousel-item">
        	      		<img class="imgFixed d-block img-fluid" src="https://img.mshanken.com/d/cao/bolt/2019-04/bslv19-std-800x450-2.jpg" alt	="Second slide">
        	    	</div>
        	    	<div class="carousel-item">
						<img class="imgFixed d-block img-fluid" src="https://bigten.org/images/2019/9/11/19MBBT_Header_v2.jpg?width=1024&mode=crop" alt="Third slide">
        	    	</div>
        	  	</div>

        	  <!--
        	  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        	    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        	    <span class="sr-only">Previous</span>
        	  </a>

        	  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        	    <span class="carousel-control-next-icon" aria-hidden="true"></span>
        	    <span class="sr-only">Next</span>
        	  </a>
			-->
			</div>
		</div>


        <div class="body">
			<div class="parentContainer">
				<div class="header">
					<H2>Recommend for you</H2>
				</div>
				<div>
					@foreach ($products as $product)
					<a href="#">
						<div class="ch">
							<img class="im" src="@asset('views/public/image/' . $product['image'])" alt="">
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
						</div>
					</a>
					@endforeach
				</div>
			</div>
		</div>

		<div class="footer">
			fdsalkflksdjflks
		</div>


	</div>
@endsection