$('.ch').click(function(){
	//alert($(this).attr('image'));
	$('#demo').html($(this).attr('title'));
	$('#modalImageID').attr('src',$(this).attr('image'));
});