//	基本設定
var windowProfile;

$(document).ready( function(){
		windowProfile = {
			
			rep : {
				// 'pc'	:	960,
				'pc'	:	1026,
				// 'tab'	:	460
				// 'tab'	:	734
				'tab'	:	751
				// 'tab'	:	769
				// 'tab'	:	1025
			},
			
			mode : 1 ,		//	1=PC , 2=tablet , 3=SP
			targetHeadMenu : $("#navigation"),
			browser : false,
			
			//	ブラウザ判定
			checkBrowserType:function(){
			
				if( !windowProfile.browser ){
					var ua = navigator.userAgent;
					var re = {};
					if ( ua.match(/msie/i) ) {		
						re.br = 'IE';
						if( ua.match(/msie [6.]/i) ){			re.ver = 6; }	//	IE6
						else if( ua.match(/msie [7.]/i) ) {		re.ver = 7; }	//	IE7
						else if( ua.match(/msie [8.]/i) ) {		re.ver = 8; }	//	IE8
						else if( ua.match(/msie [9.]/i) ) {		re.ver = 9; }	//	IE9
						else if( ua.match(/msie [10.]/i) ) {	re.ver = 10; }	//	IE10
						else if( ua.match(/msie [11.]/i) ) {	re.ver = 11; }	//	IE11
					}
					else if( ua.match(/chrome/i) ){			re.br = 'ch';}	//	クローム
					else if( ua.match(/safari/i) ){			re.br = 'sa';}	//	サファリ
					else if( ua.match(/firefox/i) ){			re.br = 'ff';}	//	火狐
					else if( ua.match(/opera/i) ){				re.br = 'op';}	//	オペラ
					else{										re.br = 'ot';}	//	その他
					windowProfile.browser = re;
					
				}
				
				return windowProfile.browser;
			},
			//	リサイズ処理
			reSize	:	function(){
				
				// w = $(window).width();
				w = window.innerWidth;
                // console.log(w);
				f = 0;
				
				if(		w >= windowProfile.rep.pc )	f = 1;
				else if( w >= windowProfile.rep.tab )	f = 2;
				else				f = 3;
				
				//	モード変更
				if( f != windowProfile.mode ){
					windowProfile.mode = f;
					headMenu.reSize();
				}
			}
		}	
	//	メニュー生成
	var headMenu = {
		
		plSubStr	: '_linkMenu' ,		//	サブカテゴリリンクの接尾語
		closeSub	: 0,				//	サブカテゴリ削除時間
		autoClear	: 200,				//	サブカテゴリ削除時間
		viewSubMenu	: new Array(),		//	サブカテゴリ表示フラグ
		
		//	初期設定
		init		: function(){
			
			//	マーキング
			windowProfile.targetHeadMenu.find("ul.sub-menu").each( function( lp ){
			
				kaiso = _checkso(this , 1);
				if( kaiso == 1 ){
					mk = headMenu.plSubStr + lp;
					//	対応リンクにマーキング
					
					preclass = $(this).parent().attr( 'class' ).split(' ');

					$(this).prev().addClass( mk );
					$(this).prev().addClass( '_havaSub' );
					$(this).addClass( mk );
					
					// 現在表示 OFF
					headMenu.viewSubMenu[mk] = {
						view	: 0 ,
						hit		: false,
						timer	: {}
					}
					
					cloneObj = $(this).clone();
					cloneObj.removeClass("sub-menu");
					
					var plObj = $('<div/>');				
					plObj.attr("class" , cloneObj.attr("class"));
					
					plObj.addClass( '_subCategory' );
					plObj.addClass( preclass[0] );
					plObj.append(cloneObj);
					
					windowProfile.targetHeadMenu.append( plObj );
					
					$(this).remove( );
				}
				
			});			

			
			//	サブカテゴリ非表示
			windowProfile.targetHeadMenu.find( "ul.sub-menu" ).show();

			windowProfile.targetHeadMenu.find( "div._subCategory" ).hide();
			
			function _checkso( obj , co ){
				if( $($(obj).parent().parent()).hasClass('sub-menu') ) _checkso( $($(obj).parent()) , co++ );
				return co; 
			}
			$("*").click( function(){
				headMenu.closeSubCategoryClick();
			});
			
			
			
			
		},
		//	デバッグ
		debugStr		: function(){
		


		},
		//	サブカテゴリ表示
		viewSubCategory	:	function( cl ){
		
			headMenu.viewSubMenu[cl].view = 1;
			
			//	親ボタン位置取得
			preObj = windowProfile.targetHeadMenu.find( "a."+cl );
			subMenuObj = windowProfile.targetHeadMenu.find( "div."+cl );

            var widthSubCate = window.innerWidth;
            if(widthSubCate > 1200) {
                subMenuObj.css({
                    "z-index" : 10000,
                    "left"	: 0 ,
                    "top"	: ($("#main-nav").offset().top + $("#main-nav").find("img").outerHeight(true) )+ 'px' //0204bk
                });
            } else {
                subMenuObj.css({
                    "z-index" : 10000,
                    "left"	: 0 ,
                    "top"	: ($("#main-nav").offset().top + $("#main-nav").find("img").outerHeight(true) - 1.5 )+ 'px' //0204
                });
            }
	
			
			subMenuObj.show( 0 );
			
	//		if(windowProfile.checkBrowserType().br == 'IE' && 8 >= windowProfile.checkBrowserType().ver )	return;
			
			 $(subMenuObj).find("table").css( 'margin-top' , 0 );
			h = subMenuObj.outerHeight(true);
			
			$(subMenuObj).find("table").css( 'margin-top' , -h+'px' );
	//		alert( $(subMenuObj).find("table").css( 'margin-top' ) )
			$(subMenuObj).find("table").animate({ 
				'margin-top' : 0+'px'
			}, headMenu.autoClear ,function(){
				headMenu.viewSubMenu[cl].view = 2;
			    $(this).queue([]);      // queueを空にする
			    $(this).stop(); 
			});
			
		},
		
		//	閉じる表示
		closeSubCategory	:	function( cl ){
			
	//		clearTimeout( headMenu.viewSubMenu[cl].timer );
			if( headMenu.viewSubMenu[cl].view ){
				if( headMenu.viewSubMenu[cl].hit ){	//	マウスオーバー
				
				}else{	//	閉じる
				headMenu.viewSubMenu[cl].view = 0;
				windowProfile.targetHeadMenu.find( "div."+cl ).hide( );
/*
					h = windowProfile.targetHeadMenu.find( "div."+cl ).outerHeight(true);
					windowProfile.targetHeadMenu.find( "div."+cl + ' table' ).animate({
						'margin-top' : -h+'px'
						
					} , headMenu.autoClear ,function(){
						windowProfile.targetHeadMenu.find( "div."+cl ).hide( 0);
						headMenu.viewSubMenu[cl].view = 0;
						
					});
*/
				}
			}
		},

		//	閉じる表示
		closeSubCategory2	:	function( cl ){
			if( headMenu.viewSubMenu[cl].view == 2 ){
			
		
				headMenu.viewSubMenu[cl].view = 1;
				
				//	親ボタン位置取得
				preObj = windowProfile.targetHeadMenu.find( "a."+cl );
				subMenuObj = windowProfile.targetHeadMenu.find( "div."+cl );

                 var widthSubCateClose = window.innerWidth;
                 if(widthSubCateClose > 1200) {
                     subMenuObj.css({
                         "z-index" : 10000,
                         "left"	: 0 ,
                         "top"	: ($("#main-nav").offset().top + $("#main-nav").find("img").outerHeight(true) )+ 'px' //0204bk
                     });
                 } else {
                     subMenuObj.css({
                         "z-index" : 10000,
                         "left"	: 0 ,
                         "top"	: ($("#main-nav").offset().top + $("#main-nav").find("img").outerHeight(true) - 1.5 )+ 'px' //0204
                     });
                 }
		
				
				subMenuObj.show( 0 );
				h = subMenuObj.outerHeight(true);
		//		alert($(subMenuObj).find("table").css( 'margin-top'));
				$(subMenuObj).find("table").css( 'margin-top' , 0+'px' );
				$(subMenuObj).find("table").animate({ 
					'margin-top' : -h+'px'
				}, headMenu.autoClear ,function(){
				 	headMenu.viewSubMenu[cl].view = 0; 
				    $(this).queue([]);      // queueを空にする
				    $(this).stop(); 
					subMenuObj.hide( 0 );
				});
			}
		},



		
		//	閉じるチェック
		closeSubCategoryClick	:	function( ){
			for( var cl in headMenu.viewSubMenu  ){
		//		alert(cl+':'+headMenu.viewSubMenu[cl].hit)
				if( !headMenu.viewSubMenu[cl].hit){
					headMenu.closeSubCategory(cl);
				}	
			}
		},
		
		subCategoryAllClose : function(){
			//	現在表示中のサブメニューを閉じる
			for( var cl in headMenu.viewSubMenu  ){
				
		//		if( headMenu.viewSubMenu[cl].timer )	clearTimeOut( headMenu.viewSubMenu[cl].timer );
				headMenu.viewSubMenu[cl].hit = false;
				windowProfile.targetHeadMenu.find( "div."+cl ).hide();
				headMenu.viewSubMenu[cl].view = 0;
			}
		},


		//	画面リサイズ
		reSize		: function(){
			if( windowProfile.mode == 1 || windowProfile.mode == 2 ){		//	上部固定
				windowProfile.targetHeadMenu.find( "div._subCategory" ).css({
					'position'	: 'absolute',
					'top'		: 0,
					'left'		: 0
				});
				
				
				if( windowProfile.targetHeadMenu.find("li").find( "div._subCategory" ).length > 0 ){
					windowProfile.targetHeadMenu.find( "div._subCategory" ).each(function(){
						$(this).appendTo( windowProfile.targetHeadMenu );
					});
				}
				
				//	ヘッダーメニュー調整
				windowProfile.targetHeadMenu.find( 'ul#main-nav' ).show();
				windowProfile.targetHeadMenu.find( 'p#_headMenuHideBtn' ).remove();
				if( $("#headImageWrapp").length )		$("#headMenuWrap").insertBefore("#headImageWrapp");
				
				//	テーブル生成
				if( windowProfile.mode == 1 )	turnTR = 4;
				else							turnTR = 3;
				
				windowProfile.targetHeadMenu.find( "div._subCategory" ).each(
					function(){
					
						var inTable = '';
						var tCount = 0;
						if( $(this).find("table").length == 0 ){
							$(this).append("<table></table>");
						}
						$(this).find("table").empty().show();
						
						$( $(this).find("ul:first").find("li")).each(
							function(){
								if( $(this).parent("ul").attr("class") != 'sub-menu' ){
									tCount ++;
									if( (tCount % turnTR ) == 1 )		inTable += '<tr>';
									
									inTable += '<td><ul><li class="'+$(this).attr("class")+'">' + $(this).html() + '</li></ul></td>';

									if( (tCount % turnTR ) == 0 )		inTable += '</tr>';
									
								}
							}
							
						);
						
						if( (tCount % turnTR ) != 0 )		inTable += '<td colspan="' + (turnTR - (tCount % turnTR ))+ '"></td></tr>';
						$(this).find("table").html(inTable);
//						alert( $(this).find("table").height() )
						$(this).find("ul:first").hide();
					}
				);
				
			}else{

				windowProfile.targetHeadMenu.find( "div._subCategory" ).each(function(){				
					$(this).find("ul:first").show();
					$(this).find("table").empty().hide();
				});

				windowProfile.targetHeadMenu.find( "div._subCategory" ).css({
					'position'	: 'static',
					'top'		: 0,
					'left'		: 0
				});
				//	サブカテゴリリスト内に
				windowProfile.targetHeadMenu.find( "div._subCategory" ).each(function(){
					clsses = $(this).attr("class").split(' ');
					
					target = windowProfile.targetHeadMenu.find( "a."+clsses[0] );
					$(this).insertAfter( target );
				
				});
				
				windowProfile.targetHeadMenu.find( 'ul#main-nav' ).hide();
				
				
				//	ヘッダーメニュー調整
				if( !windowProfile.targetHeadMenu.find( 'p#_headMenuHideBtn' ).length ){
					windowProfile.targetHeadMenu.append( '<p id="_headMenuHideBtn">▼</p>' ).append();
					
					$('p#_headMenuHideBtn').show();
					
					$('p#_headMenuHideBtn').click( function(){
						if( $(this).html( ) == '▼' ){
							windowProfile.targetHeadMenu.find( 'ul#main-nav' ).show(100);
							$(this).html('▲') ;
						}else{
							windowProfile.targetHeadMenu.find( 'ul#main-nav' ).hide(100);
							$(this).html('▼') ;
						}
					});
				}
				headMenu.subCategoryAllClose();
				if( $("#headImageWrapp").length )			$("#headImageWrapp").insertBefore("#headMenuWrap");

			}


			//	クリックナビゲート（サブカテゴリ表示）
			$.fn.clickLink = function(){
				switch( windowProfile.mode ){
					case 1:	
						$(this).click( function(){
							return _viewSubMenu( this );
						});
						break;
					case 2:	
						$(this).click( function(){
							return _viewSubMenu( this );
						});
						break;
					case 3:
						$(this).click( function(){
							// return _viewSubMenu( this );
						});
						break;
					
				}
				
				var _viewSubMenu = function( obj ){
					if( $(obj).hasClass("_havaSub") ){	//	子カテゴリ持ち
						classes = $(obj).attr("class").split(' ');
												
						if( !headMenu.viewSubMenu[classes[0]].view ){	//	サブカテゴリ非表示
							headMenu.viewSubCategory(classes[0]);
							return false;
						}else{	//	表示中
							headMenu.closeSubCategory2(classes[0]);
							return false;
						}
					}
				} 
				
			}
			$.fn.hitBoxCheck = function(){
			
				$(this).mouseover(	function(){	_hitCheck( this , true , true );		} );
				$(this).mouseout(	function(){	_hitCheck( this , false , true );		} );
				
				function _hitCheck( obj , flg , stopflg ){
					
					function __checgeFlg( cls , flg ){
						if( headMenu.viewSubMenu[cls] != undefined ){
							headMenu.viewSubMenu[cls].hit = flg;
							return true;
						}
						
						return false;
					}
					
					if( $(obj).attr("class") == undefined )	return false;
					
					clsses = $(obj).attr("class").split(' ');

					if( __checgeFlg( clsses[0] , flg ) ){
						_hitCheck( $("a." + clsses[0]).parent().parent() , flg , false );
						
						
						//	子階層
						if( stopflg && obj.tagName == 'UL' ){
							$(obj).find("a").each( function(){
								 _hitCheck( this , flg , false );
							 } );
						}
						
					}
				}
			}
			
			headMenu.subCategoryAllClose();
			
			windowProfile.targetHeadMenu.find( "ul#main-nav li a" ).unbind("click , mouseover , mouseout");
			windowProfile.targetHeadMenu.find( "div._subCategory" ).unbind("mouseover , mouseout");
			
			windowProfile.targetHeadMenu.find( "ul#main-nav li a" ).clickLink();
			
			windowProfile.targetHeadMenu.find( "ul#main-nav a" ).hitBoxCheck();
			windowProfile.targetHeadMenu.find( "div._subCategory" ).hitBoxCheck();
						
		}
			
		

	}
	
	
	//	初期セットアップ
	$(window).load(function(){

		windowProfile.reSize();

		headMenu.init();
		headMenu.reSize();
		
		
	/*
		windowProfile.targetHeadMenu.find( 'ul#main-nav li img' ).hover(
			function( ){		$(this).fadeTo( 0,0.6 ) },
			function( ){		$(this).fadeTo( 0,1 ) }
		);
	*/	

		//	ウィンドウリサイズ
		var resizeTimer = false;
		var isTablet = false;
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.indexOf('ipad') > -1 || ua.indexOf('macintosh') > -1 && 'ontouchend' in document || ua.indexOf('android') > 0){
            isTablet = true;
        }
		$(window).resize(function() {
		    if (resizeTimer !== false)       clearTimeout(resizeTimer);		 
            if(isTablet) {
                // タブレットのときは画面幅リサイズでサブメニューを非表示にしない（ツールバーの大きさ変わることへの対応）
                // console.log('Tablet');
                // console.log(ua);
            } else {
                // console.log('notTablet');
                // console.log(ua);
                if( windowProfile.mode == 1 || windowProfile.mode == 2 ) {
                    headMenu.subCategoryAllClose();   
                }
            }
		    resizeTimer = setTimeout(function() {
				windowProfile.reSize();
				
		    }, 200);
		});		
		
	});
	
	//	アコーディオンテーブル
	if( $("table.Accordion").length ){
		var changeAccordionIcon = function( obj , target ){
		
			if( $(obj).parent().find("tbody").is(':hidden') ){
				$(obj).find(target).css( "background-image" , "url( /nig/images/common/acd_icon.gif )" );
			}else{
				$(obj).find(target).css( "background-image" , "url( /nig/images/common/acd_icon_open.gif )" );
			}
		
		}
	
		if( 960 > $(window).width() )	$("table.Accordion").find("tbody").hide();
		else{
			$("table.Accordion").find("thead").each( function(){
				if( $(this).find(".oranizationFontB_open").length ){
					changeAccordionIcon( this , 'span.oranizationFontB_open');
				}else if( $(this).parent().hasClass("listTable") ){
					changeAccordionIcon( this , 'th');
				}
			});
		}
		
		
		$("table.Accordion").find("thead").css({
			"cursor"			: "crosshair"
		});
		
		
		$("table.Accordion").find("thead").click( function(){
		
			$(this).parent().find("tbody").toggle();
			
			if( $(this).find(".oranizationFontB_open").length ){
				changeAccordionIcon( this , 'span.oranizationFontB_open');
			}else if( $(this).parent().hasClass("listTable") ){
				changeAccordionIcon( this , 'th');
			}
			
		});
	}
	
});

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-52062731-1', 'nig.ac.jp');
  ga('send', 'pageview');

