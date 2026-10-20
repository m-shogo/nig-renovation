
$(document).ready( function(){
	//	コンテンツ高さ調整
	
	var autoContentsResize = function(){
		// if( $(window).width() >= windowProfile.rep.tab ){
		if( window.innerWidth >= windowProfile.rep.tab ){
			$(".container").find("div._hightAdust").css( 'height' , 'auto');
			max = 0;

			// $(".container").each(function(){
			// 	max = 0;
			// 	$(this).find("div._hightAdust").each(function(){
			// 		if( $(this).outerHeight(true) > max ){
			// 			max = $(this).outerHeight(true) ;
			// 		}
			// 	});	
			// 	$(this).find("div._hightAdust").css({
			// 		 'height'		: max + 'px' ,
			// 		 'max-height'	: max + 'px'
			// 	});
			// });

            $(".container div._hightAdust").each(function(){
                if( $(this).outerHeight(true) > max ){
                    max = $(this).outerHeight(true) ;
                }
			});
            $(".container div._hightAdust").css({
                'height'		: max + 'px' ,
                'max-height'	: max + 'px'
            });

		}else{
			$(".container").find("div._hightAdust").css({
				'height'		: 'auto' ,
				'max-height'	: 'auto'
			});
		}
		//	iframe横幅制御
		$("iframe#fbook").css({
			'width'			: $(".container").find("div._hightAdust").width() + 'px'
		});
		
		$("iframe#fbook").width(  $(".container").find("div._hightAdust").width() );
//		alert( $(".container").find("div._hightAdust").width()  + ':' + $("iframe#fbook").width()  )
	}
	/*
	var _resizeTimer = false;
	$(window).resize(function() {
	    if (_resizeTimer != false)       clearTimeout(_resizeTimer);		    
	    _resizeTimer = setTimeout(function() {
			autoContentsResize();
			
	    }, 200);
		return true;
	});*/
	
//	autoContentsResize();
	
	
	
var _globalLock = 0;
var _size = { w: 0, h: 0 };
var _ie = document.uniqueID;
var _quirks = (document.compatMode || "") !== "CSS1Compat";
var _ieroot = _quirks ? "body" : "documentElement";

function getInnerSize() {
  var root = _ie ? document[_ieroot] : window;
  return { w: root.innerWidth  || root.clientWidth,
           h: root.innerHeight || root.clientHeight };
}

// resize agent
function agent() {
  function loop() {
    if (!_globalLock++) {
      var size = getInnerSize();
      if (_size.w !== size.w || _size.h !== size.h) { // resized
        _size = size; // update
        autoContentsResize();
      }
      setTimeout(function() { _globalLock = 0; }, 0); // delay unlock
    }
    setTimeout(loop, 100);
  }
  setTimeout(loop, 100);
}	
	agent();
	
});



$(window).load(function(){
	$('#slideWrap #bxslider').children('li').each(function(i,elem){
		$(this).attr('data-num',i);
	});
	$('#slideWrap #bxslider').bxSlider({
		auto: true,
		// mode: 'fade',
		mode: 'horizontal',
		controls: true,
		nextText: '',
		prevText: '',
		// minSlides: 1,
        // maxSlides: 1,
        // moveSlides: 1,
        slideWidth: 1200,
		// onSliderLoad: function(cr){
		// 	$('#slideWrap #bxslider').children('li[data-num="' + cr + '"]').addClass('active');
		// 	$('#slideWrap #bxslider').animate({
		// 		opacity: 1
		// 	},500);
		// },
		// onSlideBefore: function($slideElement, oldIndex, newIndex){
		// 	$('#slideWrap #bxslider').children('li').removeClass('active').animate({
		// 		// opacity: 0.2
		// 		opacity: 0
		// 	},300);
		// },
		// onSlideAfter: function($slideElement, oldIndex, newIndex){
		// 	$('#headImageWrapp').find('.bx-viewport > ul > li[data-num="' + newIndex + '"]').addClass('active').animate({
		// 		opacity: 1
		// 	},300);
		// }
        // onSlideAfter: function () { slider.startAuto(); }
	});

});

//pickup部分のスライダー
$(function() {
	var sliderType;
	var breakpoint = 480;
	var spOption = {
        auto: true,
		speed: 600,
		pause: 3600,
		mode: 'horizontal',
		controls: true,
		nextText: '',
		prevText: '',
		nextSelector: '.pickup-nextArrow',
		prevSelector: '.pickup-prevArrow',
		minSlides: 2,
		maxSlides: 2,
		moveSlides: 1,
		slideWidth: 128,
		slideMargin: 0,
        onSlideAfter: function () { slider.startAuto(); }
	}
	var pcOption = {
		auto: true,
		speed: 600,
		pause: 3600,
		mode: 'horizontal',
		controls: true,
		nextText: '',
		prevText: '',
		nextSelector: '.pickup-nextArrow',
		prevSelector: '.pickup-prevArrow',
		minSlides: 3,
		maxSlides: 5,
		moveSlides: 1,
		slideWidth: 183,
		slideMargin: 30,
        onSlideAfter: function () { slider.startAuto(); }
	}
 
	var slider = $('#pickup-bxslider').bxSlider();
	$(window).on('load resize', function() {
		sliderSet();
	});
 
	function sliderSet() {
		// var windowWidth = $(window).width();
		var windowWidth = window.innerWidth;
		if (windowWidth <= breakpoint && sliderType != 'sp') {
			slider.reloadSlider(spOption);
			sliderType = 'sp';
		} else if (windowWidth > breakpoint && sliderType != 'pc') {
			slider.reloadSlider(pcOption);
			sliderType = 'pc';
		}
	}
});

