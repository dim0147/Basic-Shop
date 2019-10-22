@extends('layouts.mainlayout')


@section('content')
<form >
    <!-- Name of input element determines name in $_FILES array -->
    title: <input name="title">
    description: <input name="description">
    price: <input name="price">
    status: <input name="status">
    rate: <input name="rate">


    Header: <input name="header" type="file" >
    Thumnail<input name="thumbnail[]" type="file" multiple required/>
    <input type="button" id="sub" value="Send File" />
</form>
@endsection

@section('javascript')
    <script>
        $('#sub').click(function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form')[0]);
        alert('submit')
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