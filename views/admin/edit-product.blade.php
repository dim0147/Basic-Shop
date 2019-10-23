@extends('layouts.mainlayout')

@section('content')
    
<form style="padding-top: 100px;" method="POST">
    @foreach ($product as $prod)
        Title: <input type="text" name="title" value="{{$prod['title']}}"><br>
        Price: <input type="text" name="price" value="{{$prod['price']}}"><br>
        Status: <input type="text" name="status" value="{{$prod['status']}}"><br>
        Rate: <input type="text" name="rate" value="{{$prod['rate']}}"><br>
        Description: <textarea name="description" id="" cols="30" rows="10">{{$prod['description']}}</textarea><br>
        Header: <input type="file" name="header"><br>
        <img src="@asset('views/public/image/'.$prod['image'])" alt="header" style="width:50px; height:30px;"><br>
        Thumbnail: <input type="file" name="thumbnail[]" multiple><br>
        @foreach ($prod['listImage'] as $id => $item)
            <img class="thumbnailImg" id="{{$id}}" src="@asset('views/public/image/'.$item)" alt="thumbnail" style="width:50px; height:30px; margin: 10px;">
        @endforeach
    @endforeach
    <br>
    <button class="btn btn-primary submit">Submit</button>

</form>

@endsection


@section('javascript')

<script>
    var imageDelete = [];
$('.submit').click(function(e){
    e.preventDefault();
    var data = new FormData($(this).parents('form')[0]);
    data.append('imgDel', JSON.stringify(imageDelete));
    $.ajax({
        method: 'POST',
        url: 'post/edit-product',
        processData: false,
        contentType: false,
        cache: false,
        data: data,
        success: res => {
            alert(res);
            console.log(res);
        },
        error: err => {
            alert(err.responseText);
        } 
    })

})

$('.thumbnailImg').click(function(e){
    imageDelete.push($(this).attr('id'));
    alert($(this).attr('id'));
    console.log(imageDelete);
})

</script>
    
@endsection