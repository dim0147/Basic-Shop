@extends('layouts.adminlayout')

@section('css')
<link rel="stylesheet" type="text/css" href="@asset('views/admin/css/datatables.min.css')"/>
@endsection

@section('content')
<div style="padding: 10px; border: 1px solid #888;">
<div class="nofi">

</div>
<h1 class="text-center">Show user</h1>
@if(!empty($users))
<table id="table-category" class="display">
    <thead>
        <tr>
            <th style="width: 10%">ID</th>
            <th style="width: 20%">Username</th>
            <th style="width: 20%">Name</th>
            <th style="width: 20%">Type</th>
            <th style="width: 20%">Status</th>
            <th style="width: 10%">Date Create</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user['user_id']}}</td>
            <td>{{$user['username']}}</td>
            <td>{{$user['name']}}</td>
            <td>{{$user['type']}}</td>
            <td>{{$user['status']}}</td>
            <td>{{$user['date_create']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@else
<h1 class="text-center"> You currently don't have any user.</h1>
@endif
@endsection


@section('javascript')
@if(!empty($users))
    <script type="text/javascript" src="@asset('views/admin/css/datatables.min.js')"></script>
    <script type="text/javascript" src="@asset('views/admin/js/table-show-category.js')"></script>
@endif
@endsection
