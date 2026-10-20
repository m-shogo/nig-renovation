
$(document).ready( function(){
	//	コンテンツ高さ調整
	
	var autoContentsResize = function(){
		if( $(window).width() >= windowProfile.rep.tab ){
			max = 0;
			$(".container").each(function(){
				max = 0;
				$(this).find("div._hightAdust").each(function(){
					if( $(this).height() > max ){
						max = $(this).height() ;
					}
				});	
				$(this).find("div._hightAdust").css( 'height' , max + 'px');
			});
		}else{
			$(".container").find("div._hightAdust").css( 'height' , 'auto');
		}
	}
	
	var resizeTimer = false;
	
	$(window).resize(function() {
	    if (resizeTimer !== false)       clearTimeout(resizeTimer);		    
	    resizeTimer = setTimeout(function() {
			autoContentsResize();
			
	    }, 200);
	});	
	
	autoContentsResize();
	
	//	画像読込み時にも実行
	var count = 0;
	
	$(".container").find("img").one('load',function(){
		count ++;
		if( count == $(".container").find("img").length ) autoContentsResize();
	
    });


	//	スライドショー
	
	$.fn.topSlideShow = function(){
		
		var settings = {
		
			thisObj		: $(this),
			slideBox	: $(this).find("#slider"),
			
			changeTime	: 5000,
			fadeTime	: 150 ,
			
			running	: false,
			nowChange	: false,
			nownum	: 0,
			beforCt	: 0,
			controler : {},
			
			timer	: false,
			images	: Array(),
			gBox	: null,
		
			init : function(){
				if( settings.slideBox.find("img").length > 1){
					
				
					settings.slideBox.find("img").hide();
					//	1つ目の未表示
					settings.slideBox.find("img:first").show();
					
					var count = 0;
					
					
/*
					settings.slideBox.find("img").one('load',function(){
						count ++;
						cpnum = settings.slideBox.find("img").index(this);
						settings.images[cpnum] = $(settings.slideBox.find("img").get(cpnum)).clone(  );
*/						
						
						//	スライド開始
						if( settings.slideBox.find("img").length > 1){
							//	コントローラー作成
							settings.controler = $('<div id="_slidController"/>');
							
							settings.slideBox.find("img").each( function(cpnum){
								settings.images[cpnum] = $(this).clone();
								settings.controler.append( '<input />' )
							});
							
							$(settings.controler.find('input').get( settings.nownum )).addClass("on");
							
							settings.controler.find('input').click( function(){
								
								thinum = settings.controler.find('input').index( this );
								settings.change( thinum );
							});
							
							//	コントローラー位置変更
							var changecontororPosi = function(){
								
								clearTimeout( changeTimer );
								
								nc = settings.thisObj.offset( ).top;
								if( settings.beforCt != nc ){
									
									settings.controler.css({
										'top'		:	(settings.thisObj.offset( ).top + 8 ) + 'px'
									});
								}
								
								changeTimer = setTimeout( function(  ){changecontororPosi( )}  , 100 );
							}
							
							changeTimer = setTimeout( function(  ){changecontororPosi( )}  , 100 );
							
							
							$("body").append(settings.controler);
							
							settings.controler.css({
								'top'		:	(settings.thisObj.offset( ).top + 8 ) + 'px'
							});
						
							settings.slideBox.find("img").remove();
							settings.slideBox.append(settings.images[settings.nownum] );
							
							settings.timer = setTimeout( function(  ){settings.change( settings.nownum + 1 )}  , settings.changeTime );
						}
//				    });
				}
			},
			
			change	: function(num){
				
				if( settings.nowChange )		return;
				
				num = num % settings.images.length;
				//	同じ場合何もせず変更
				if( settings.nownum == num )	return;
				
				settings.nowChange = true;
				clearTimeout( settings.timer );
				
				$(settings.controler.find('input').get( settings.nownum )).removeClass("on");
	
				// 現在画像フェード
				$(settings.slideBox.find("img")).fadeTo( settings.fadeTime , 0 , function(){
					
					settings.slideBox.find("img").remove();
					
					settings.slideBox.append(settings.images[num]);
					settings.slideBox.find("img").fadeTo(0,0);
					
					
					settings.slideBox.find("img").fadeTo(  settings.fadeTime , 1 ,function(){
						settings.nowChange = false;
						settings.nownum = num;
						$(settings.controler.find('input').get( settings.nownum )).addClass("on");
						settings.timer = setTimeout( function(  ){settings.change( settings.nownum + 1 )}, settings.changeTime );
					}); 
					
				});
				
			}
			
		}
		
		settings.init();


	}

	$("#headImageWrapp").topSlideShow();
	
});
