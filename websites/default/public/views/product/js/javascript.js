$('.ch').click(function(){
	$('#modalID').attr('value',$(this).attr('identify')).html($(this).attr('identify'));
	
	$('#modalTitle').html($(this).attr('title'));
	$('#modalDescription').html($(this).attr('des'));
	$('#modalImage').attr('src',$(this).attr('image'));
	$('.show-dt').attr('href', '/product/detail?id=' + $(this).attr('identify'));
});