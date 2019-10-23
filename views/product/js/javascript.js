$('.ch').click(function(){
	//alert($(this).attr('image'));
	$('#demo').html($(this).attr('title'));

	//$('#modalImageID').html($(this).attr('image'));
	$('#modalImage').attr('src',$(this).attr('image'));
	$('#modalDescription').html($(this).attr('des'));
});