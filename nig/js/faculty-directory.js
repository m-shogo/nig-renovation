$(document).ready( function(){
_nowmode = 0;
_nowChange = false;
_mainObj = $("#realViewBox");
$("#facultyBox").hide();
var _sortTable = function( turn ){
_turn  = "turn" + turn;
_class = "split" + turn;
_target = 0;
$("#facultyBox").find("div.faculyContents").each( function(){
if( $(this).hasClass( _turn ) )	_target++;
$( _mainObj.find( "div."+_class ).get(_target) ).append( $(this).clone() );
});
}
var _contentsSort = function(){
if( _nowmode != windowProfile.mode ){
if( _nowChange )	return;
_nowmode = windowProfile.mode;
_mainObj.empty();	//	要素削除
switch( _nowmode ){
case 1:	//PC
_mainObj.append( '<div class="split3"></div><div class="split3"></div><div class="split3"></div><br class="clear">' );
_sortTable( 3 );
break;
case 2:	//TAB
_mainObj.append( '<div class="split2"></div><div class="split2"></div><br class="clear">' );
_sortTable( 2 );
break;
case 3:	//スマホB
_mainObj.append( '<div class="split1"></div>' );
_sortTable( 1 );
_mainObj.find("ul").hide();
_mainObj.find("p.title").css({
"cursor" : "pointer"
}).click(function(){
_target = $(this).next();
_target.toggle();
});
break;
}
}
}
windowProfile.reSize();
_contentsSort();
var _resizeTimer = false;
$(window).resize(function() {
if (_resizeTimer !== false)       clearTimeout(_resizeTimer);		 
_resizeTimer = setTimeout(function() {
windowProfile.reSize();
_contentsSort();
}, 200);
});		
});