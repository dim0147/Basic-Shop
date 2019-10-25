@extends('layouts.mainlayout')

@section('css')
    <link rel="stylesheet" type="text/css" href='@asset('views/admin/css/index.css')'>
@endsection

@section('content')

<div>
    <form method="POST">
        <!-- Name of input element determines name in $_FILES array -->
        Send this file:
        <input name="header" value="4"  type="file"/>
        <input name="thumbnail[]" value="1"  type="file" multiple>
        <input id="sub" type="button" value="Send File" />
        title: <input name="title" value="4"  />
        description: <input name="description" value="4"  />
        price: <input name="price" value="4"  />
        status: <input name="status" value="4"  />
        rate: <input name="rate" value="4"  />
        @foreach ($categorys as $item)
            <input type="checkbox" id="{{$item['id']}}" value="{{$item['name']}}">{{$item['name']}}
        @endforeach
    </form>
</div>

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