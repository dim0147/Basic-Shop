@extends('layouts.mainlayout')

@section('content')
    
<form style="padding-top: 100px;" method="POST">
    @foreach ($product as $prod)
       <input type="hidden" name="id" value="{{$prod['id']}}"><br>
        Title: <input type="text" name="title" value="{{$prod['title']}}"><br>
        Price: <input type="text" name="price" value="{{$prod['price']}}"><br>
        Status: <input type="text" name="status" value="{{$prod['status']}}"><br>
        Rate: <input type="text" name="rate" value="{{$prod['rate']}}"><br>
        Description: <textarea name="description" id="" cols="30" rows="10">{{$prod['description']}}</textarea><br>
        Header: <input type="file" name="header"><br>
        <img sid="{{$prod['image']}}" src="@asset('views/public/image/'.$prod['image'])" alt="header" style="width:50px; height:30px;"><br>
        Thumbnail: <input type="file" name="thumbnail[]" multiple><br>
        @foreach ($prod['listImage'] as $id => $item)
            <img sid="{{$item}}" class="thumbnailImg" id="{{$id}}" src="@asset('views/public/image/'.$item)" alt="thumbnail" style="width:50px; height:30px; margin: 10px;">
        @endforeach
        <br>
            
        @foreach ($prod['categoryName'] as $id => $item)
            <input class="category" idCategory="{{$id}}" type="checkbox" value='{{$item}}' checked>{{$item}}<br>
        @endforeach

        @foreach ($category as $id => $item)
            <input class="category" idCategory="{{$id}}" type="checkbox" value='{{$item['name']}}'>{{$item['name']}}<br>
        @endforeach
    @endforeach
    <br>
    <button class="btn btn-primary submit">Submit</button>

</form>

@endsection


@section('javascript')

<script>
    var imgIdDelete = [];
    var imgNameDelete = [];
    var categoryDelete = ['3', '1'];
    var categoryAdd = {
        '2': 'Killing',
        '6': 'Multiplayer',
        '7': 'Indle'
    };
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
            console.log(res);
        },
        error: err => {
            alert(err.responseText);
        } 
    })

})

$('.thumbnailImg').click(function(e){
    imgIdDelete.push($(this).attr('id'));
    imgNameDelete.push($(this).attr('sid'));
    alert($(this).attr('id'));
    console.log(imgNameDelete);
})

</script>
    
@endsection