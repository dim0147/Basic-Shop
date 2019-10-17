@extends('layouts.mainlayout')

@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/product/css/index.css')'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
	<div class="main">
		<div class="parentContainer">
			<div class="container">
				<H2>Recommend for you</H2>
			</div>
	
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
		<div class="footer">
			fdsalkflksdjflks
		</div>
	</div>
@endsection