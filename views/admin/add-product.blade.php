@extends('layouts.mainlayout')


@section('content')
<form style="padding-top: 100px;">
    <!-- Name of input element determines name in $_FILES array -->
    title: <input name="title">
    description: <input name="description">
    price: <input name="price">
    status: <input name="status">
    rate: <input name="rate"><br> 


    Header: <input name="header" type="file" ><br> 
    Thumnail<input name="thumbnail[]" type="file" multiple required/><br> 
    <input type="button" id="sub" value="Send File" /><br> 
    @foreach ($categorys as $category)
<input type="checkbox" id="{{$category['id']}}" value="{{$category['name']}}">{{$category['name']}} <br> 
    @endforeach
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
            // xhr: function() {
            //     var myXhr = $.ajaxSettings.xhr();
            //     return myXhr;
            // },
            success: function (data) {
                alert("Data Uploaded: "+data);
            },
            error: function (err) {
                alert('err ' + err.responseText);
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