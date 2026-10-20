$(function(){
	$('.yearSelectButton').on('click',function(){
		// $('.yearSelectWrap li').toggleClass('is-active');
		$('.yearSelectWrap li').not('._first_list-item').slideToggle();
		// $(this).toggleClass('is-active');
	});
});
$(function(){
	$('._first_list-item').on('click',function(){
		// $('.yearSelectWrap li').toggleClass('is-active');
		$('.yearSelectWrap li').not('._first_list-item').slideToggle();
		// $(this).toggleClass('is-active');
	});
});