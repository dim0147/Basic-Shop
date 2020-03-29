@extends('layouts.adminlayout')

@section('content')
<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
</style>

<!-- product -->
<div class="card text-center" style="margin-bottom: 50px;margin-top: 50px;">
  <div class="card-header">
    <h1>Recent product</h1>
  </div>
  <div class="card-body">
    <h5 class="card-title">Product recently added</h5>
    @if(!empty($products))
    <p class="card-text">Showing recently product has been added.</p>
    <table>
  <tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Status</th>
    <th>Categorys</th>
  </tr>
  @foreach($products as $product)
    <tr>
      <td>{{$product['id']}}</td>
      <td><img src="@asset('views/public/image/'.$product['image'])" style="width: 90px; height: 60px"></td>
      <td><a href="/product/detail?id={{$product['id']}}" style="text-decoration: none;color: green; font-weight: bold">{{$product['title']}}</a></td>
      <td style="color:red">${{$product['price']}}</td>
      <td>{{$product['status']}}</td>
      <td>{{$product['categorys']}}</td>
    </tr>
  @endforeach
</table>
<br>
    <a href="/admin/show-product" class="btn btn-primary">Show more</a>
    @else
    <p class="card-text">No product was recently added.</p>
    @endif
  </div>
  <div class="card-footer text-muted">
    Update recently
  </div>
</div>
    
<!-- orders -->
<div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Order recently placed</h5>
        @if(!empty($orders))
        <p class="card-text">Showing recently order has been placed.</p>
    <table>
  <tr>
    <th>ID</th>
    <th>Name User</th>
    <th>Status</th>
    <th>Email</th>
    <th>Address</th>
    <th>PaymentID</th>
  </tr>
  @foreach($orders as $order)
    <tr>
      <td>{{$order['order_id']}}</td>
      <td>{{$order['user_name']}}</td>
      <td>{{$order['status']}}</a></td>
      <td>{{$order['email']}}</td>
      <td>{{$order['address']}}</td>
      <td>{{$order['paymentID']}}</td>
    </tr>
  @endforeach
</table>
@else
<p class="card-text">No orders recently placed.</p>
@endif
      </div>
    </div>
  </div>

<!-- user -->
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">User recently join</h5>
        @if(!empty($users))
        <p class="card-text">Showing recently user has joined.</p>
    <table>
  <tr>
    <th>ID</th>
    <th>Name User</th>
    <th>Status</th>
    <th>Date join</th>
  </tr>
  @foreach($users as $user)
    <tr>
      <td>{{$user['user_id']}}</td>
      <td>{{$user['name']}}</td>
      <td>{{$user['status']}}</a></td>
      <td>{{$user['date_create']}}</td>
    </tr>
  @endforeach
</table>
@else
<p class="card-text">No user recently join.</p>
@endif
      </div>
    </div>
  </div>
</div>

@endsection