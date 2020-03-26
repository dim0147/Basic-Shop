@extends('layouts.adminlayout')

@section('css')
<link rel="stylesheet" type="text/css" href="@asset('views/admin/css/datatables.min.css')"/>
@endsection

@section('content')
<div style="padding: 10px; border: 1px solid #888;">
<div class="nofi">

</div>
<h1 class="text-center">Show category</h1>
@if(!empty($categorys))
<table id="table-category" class="display">
    <thead>
        <tr>
            <th style="width: 25%">ID</th>
            <th style="width: 30%">Name</th>
            <th style="width: 35%">Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorys as $category)
        <tr>
            <td>{{$category['id']}}</td>
            <td>{{$category['name']}}</td>
            <td>{{$category['description']}}</td>
            <td>
                <a href="/admin/edit-category?id={{$category['id']}}"><button type="button" class="btn btn-warning editbtn"><i class="fas fa-edit"></i></button></a>
                <button type="button" class="btn btn-danger rmvbtn"><i class="fa fa-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@else
<h1 class="text-center"> You currently don't have any category.</h1>
@endif
@endsection


@section('javascript')
@if(!empty($categorys))
    <script type="text/javascript" src="@asset('views/admin/css/datatables.min.js')"></script>
    <script type="text/javascript" src="@asset('views/admin/js/table-show-category.js')"></script>
@endif
@endsection