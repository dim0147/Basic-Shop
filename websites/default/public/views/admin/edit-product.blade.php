@extends('layouts.adminlayout')

@section('content')


<form action="/admin/post/add-product" method="POST">
@foreach ($product as $prod)
<h1 class="text-center">Edit product {{$prod['title']}}</h1>
<img class="text-center" src="@asset('views/public/image/'.$prod['image'])" alt="header" style="width:290px; height:250px; margin-left: 40%;">

<input type="hidden" name="id" value="{{$prod['id']}}"><br>

  <div class="form-group">
    <label for="exampleInputEmail1">Title</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="title" value="{{$prod['title']}}">
    <small id="emailHelp" class="form-text text-muted">Name your product here (required).</small>
  </div>

  
  <div class="form-group">
    <label for="exampleInputEmail1">Price</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="price" value="{{$prod['price']}}">
    <small id="emailHelp" class="form-text text-muted">Price your product (required).</small>
  </div>

  
  <div class="form-group">
    <label for="exampleInputEmail1">Status</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="status" value="{{$prod['status']}}"">
    <small id="emailHelp" class="form-text text-muted">Status your product(required)['Available'].</small>
  </div>

  
  <div class="form-group">
    <label for="exampleInputEmail1">Rate</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="rate" value="{{$prod['rate']}}"">
    <small id="emailHelp" class="form-text text-muted">Rate of product, maximum 5 (required).</small>
  </div>


  <div class="form-group">
    <label for="exampleFormControlTextarea1">DESCRIPTION</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" cols="30" rows="10" name="description" >{{$prod['description']}}</textarea>
    <small id="emailHelp" class="form-text text-muted">Descripe your product (optional).</small>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Header Image</label>
    <input type="file" name="header" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <img sid="{{$prod['image']}}" src="@asset('views/public/image/'.$prod['image'])" alt="header" style="width:90px; height:50px; margin: 20px;">
    <small id="emailHelp" class="form-text text-muted">Header Image for product (required).</small>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Thumbnail Image</label>
    <input class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" type="file" name="thumbnail[]" multiple>
    <small id="emailHelp" class="form-text text-muted">Thumbnail image for this product (required).</small>


    @foreach ($prod['listImage'] as $id => $item)
            <img sid="{{$item}}" class="thumbnailImg" id="{{$id}}" src="@asset('views/public/image/'.$item)" alt="thumbnail" style="width:90px; height:50px; margin: 20px;">
        @endforeach
        <br>

    @foreach ($categoryProduct as $id => $item)
        <input class="category" idCategory="{{$id}}" type="checkbox" value='{{$item}}' checked>{{$item}}<br>
    @endforeach

    @foreach ($category as $id => $item)
        <input class="category" idCategory="{{$item['id']}}" type="checkbox" value="{{$item['name']}}">{{$item['name']}}<br>
    @endforeach
@endforeach

<button id="sub" type="button" class="btn btn-primary submit" style="margin-left: 50%">Save changes</button>
  </div>


</form>
  

@endsection


@section('javascript')

<script>
    let arrCateOrigin = [];
    const allCheckedBox = $('.category:checkbox:checked');
    allCheckedBox.each(function(index){
        arrCateOrigin.push($(this).attr('idcategory'));
    });

    var imgIdDelete = [];
    var imgNameDelete = [];
    var categoryDelete = [];
    var categoryAdd = {
    };

    $(".category").change(function() {
    idCategoryAdd = $(this).attr('idcategory');
    if(this.checked) {
        if(arrCateOrigin.includes(idCategoryAdd)){
            const indexToRmv = categoryDelete.indexOf(idCategoryAdd);
            if (indexToRmv > -1) {
                categoryDelete.splice(indexToRmv, 1);
            }
            console.log('cate del orr ' + categoryDelete);
            console.log('cate add orr');
            console.log(categoryAdd);
            return;
        }
        categoryAdd[idCategoryAdd] = $(this).val();
        console.log('cate del ' + categoryDelete);
        console.log('cate add');
        console.log(categoryAdd);
    }
    else{
        if(arrCateOrigin.includes(idCategoryAdd) && !categoryDelete.includes(idCategoryAdd)){
            categoryDelete.push(idCategoryAdd);
            console.log('cate del orr' + categoryDelete);
            console.log('cate add orr');
            console.log(categoryAdd);
            return;
        }
        delete categoryAdd[idCategoryAdd];
        console.log('cate del ' + categoryDelete);
        console.log('cate add' );
        console.log(categoryAdd);

    }
});

$('.submit').click(function(e){
    e.preventDefault();
    var data = new FormData($(this).parents('form')[0]);
    data.append('imgDel', JSON.stringify(imgIdDelete));
    data.append('nameImgDel', JSON.stringify(imgNameDelete));
    data.append('cateAdd', JSON.stringify(categoryAdd));
    data.append('cateDel', JSON.stringify(categoryDelete));
    $.ajax({
        method: 'POST',
        url: 'post/edit-product',
        processData: false,
        contentType: false,
        cache: false,
        data: data,
        success: res => {
            alert(res); 
        },
        error: err => {
            alert(err.responseText);
        } 
    })

})

$('.thumbnailImg').click(function(e){
    const confirmDel = confirm('Are you sure want to delete this thumbnail?');
    if(!confirmDel)
        return;
    imgIdDelete.push($(this).attr('id'));
    imgNameDelete.push($(this).attr('sid'));
    $(this).remove();  
})

</script>
    
@endsection