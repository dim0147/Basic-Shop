@extends('layouts.adminlayout')


@section('content')


<div class="jumbotron text-center">
  <h1 class="display-4">ADD PRODUCT</h1>
  <hr class="my-4">
  </p>
</div>

<form action="/admin/post/add-product" method="POST">

  <div class="form-group">
    <label for="exampleInputEmail1">Title</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="title">
    <small id="emailHelp" class="form-text text-muted">Name your product here (required).</small>
  </div>

  <div class="form-group">
    <label for="exampleFormControlTextarea1">DESCRIPTION</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="description" ></textarea>
    <small id="emailHelp" class="form-text text-muted">Descripe your product (optional).</small>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Price</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="price">
    <small id="emailHelp" class="form-text text-muted">Price of product (required).</small>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Status</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="status">
    <small id="emailHelp" class="form-text text-muted">Status you want for this product (required).</small>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Rate</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="rate">
    <small id="emailHelp" class="form-text text-muted">Rate for this product (required).</small>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Header Image</label>
    <input name="header" type="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <small id="emailHelp" class="form-text text-muted">Header Image for product (required).</small>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Thumbnail Image</label>
    <input class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="thumbnail[]" type="file" multiple>
    <small id="emailHelp" class="form-text text-muted">Thumbnail image for this product (required).</small>
  </div>

  @foreach ($categorys as $item)
        <div class="form-check">
        <input class="form-check-input" type="checkbox" id="{{$item['id']}}" value="{{$item['name']}}">
        <label class="form-check-label" for="defaultCheck1">
        {{$item['name']}}
        </label>
        </div>
    @endforeach
    <small id="emailHelp" class="form-text text-muted">Please select at least 1 cateroty (required).</small>
    <div class="nofi">

    </div>
  <button id="sub" type="button" class="btn btn-primary" style="margin-left: 50%">ADD</button>
</form>


@endsection

@section('javascript')
    <script>
        function getCheckedCheckbox(){
            $arr = {};
            $.each($("input:checked"), function(){
                $arr[$(this).attr('id')] = $(this).val();
            });
            return $arr;
        }
        
        $('#sub').click(function(e){
        e.preventDefault();
        $arr = getCheckedCheckbox();
        var formData = new FormData($(this).parents('form')[0]);
        formData.append('categorys', JSON.stringify($arr));
        $.ajax({
            url: 'post/add-product',
            type: 'POST',
            success: function (data) {
                const succcessDiv = '<div class="alert alert-success" role="alert">'+
                    '  Add product success!'+
                    '</div>';
                $('.nofi').empty();
                $('.nofi').append(succcessDiv);
            },
            error: function (err) {
                console.log(err.responseText);
                const errorDiv = '<div class="alert alert-danger" role="alert">'+
                    '  Error while add product, error: '+ err.responseText
                    '</div>';
                $('.nofi').empty();
                $('.nofi').append(errorDiv);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
        });
    </script>
@endsection