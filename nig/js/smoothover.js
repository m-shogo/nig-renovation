// JavaScript Document


$(document).ready(function(){
$(function() {
$("#navigation li a").css("opacity","0");
//初期設定として画像のopacityを0にします。
$("#navigation li a").hover( //hoverです。
function () {
$(this).stop().animate({opacity: 1}, 'slow');},
//マウスオーバー時にopacityを1にします。
function () {
$(this).stop().animate({opacity: 0}, 'slow');}
//マウスアウト時にopacityを0に戻します。
);
});
});



$(document).ready(function(){
$(function() {
$("#header_right li a").css("opacity","1");
//初期設定として画像のopacityを0にします。
$("#header_right li a").hover( //hoverです。
function () {
$(this).stop().animate({opacity: 0.5}, 'slow');},
//マウスオーバー時にopacityを1にします。
function () {
$(this).stop().animate({opacity: 1}, 'slow');}
//マウスアウト時にopacityを0に戻します。
);
});
});
