@extends('layouts.adminlayout')

@section('css')
<link rel="stylesheet" type="text/css" href="@asset('views/admin/css/datatables.min.css')"/>
@endsection

@section('content')
<style>



</style>
<div style="padding: 10px; border: 1px solid #888;">
<div class="nofi">

</div>
<h1 class="text-center">Show products</h1>
@if(!empty($products))
<table id="table-category" class="display">
    <thead>
        <tr>
            <th style="width: 1%">ID</th>
            <th style="width: 10%">Image</th>
            <th style="width: 20%">Name</th>
            <th style="width: 20%">Description</th>
            <th style="width: 5%">Price</th>
            <th style="width: 5%">Status</th>
            <th style="width: 8%">Rate</th>
            <th style="width: 20%">Category</th>
            <th style="width: 60%">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{$product['id']}}</td>
            <td> <img style="width: 90px; height: 60px;" src="@asset('views/public/image/'.$product['image'])"></td>
            <td><a href="/product/detail?id={{$product['id']}}" style="text-decoration: none;color: green; font-weight: bold">{{$product['title']}}</a></td>
            <td>{!! substr($product["description"] , 0, 60) !!} ...</td>
            <td><p style="color: red;">${{$product['price']}}</p></td>
            <td>{{$product['status']}}</td>
            <td>
                @for ($i = 0; $i < $product['rate']; $i++)
                <span class="fa fa-star checked" style="color: yellow"></span>
                @endfor
                @for($i = $product['rate']; $i < 5; $i++)
                <span class="fa fa-star"></span>
                @endfor
            </td>
            <td>{{$product['categorys']}}</td>
            <td>
                <a href="/admin/edit-product?id={{$product['id']}}"><button type="button" class="btn btn-warning editbtn"><i class="fas fa-edit"></i></button></a>
                <button type="button" class="btn btn-danger rmvbtn"><i class="fa fa-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@else
<h1 class="text-center"> You currently don't have any products.</h1>
@endif
@endsection


@section('javascript')
@if(!empty($products))
    <script type="text/javascript" src="@asset('views/admin/css/datatables.min.js')"></script>
    <script type="text/javascript" src="@asset('views/admin/js/table-show-product.js')"></script>
@endif
@endsection
