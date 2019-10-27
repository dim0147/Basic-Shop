$('.ch').click(function(){
	//alert($(this).attr('image'));
	//$('#modalID').html($(this).attr('identify'));
	$('#modalID').attr('value',$(this).attr('identify')).html($(this).attr('identify'));
	
	$('#modalTitle').html($(this).attr('title'));
	$('#modalDescription').html($(this).attr('des'));
	//$('#modalImageID').html($(this).attr('image'));
	$('#modalImage').attr('src',$(this).attr('image'));
});