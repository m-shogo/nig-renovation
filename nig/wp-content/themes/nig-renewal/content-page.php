<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
//	パン屑リスト取得
global $post;
// 参照渡し用に初期化
$root_page = array();
if( is_page() ){
	ps_get_root_page( $post , $root_page );
}
$_masterPage = $root_page[count( $root_page )-1];

$_headimgSrc	= sprintf( '%simage/%s/headtitle_%s.jpg' , esc_url( home_url( '/' ) ) , $_masterPage['post_name'] , $_masterPage['post_name'] );

$_pageCategoryList = ps_get_child_page($_masterPage['ID']);

?>
	<div id="headImgBox">
		<p class="cB"><img src="<?php echo $_headimgSrc; ?>" alt="ミトシステム株式会社:<?php echo $_masterPage['post_title'] ; ?>" /></p>
	</div>

	<div id="mainBox">
	
		<ul id="inPageLinks">
			<li class="top"><a href="<?php echo esc_url( home_url( '/' ) ) ?>"><img src="<?php echo esc_url( home_url( '/' ) ) ?>image/common/linkhome.gif" alt="ミトシステム株式会社：ホームページトップ" /></a></li>
<?php
	if( is_array( $root_page )){
		foreach($root_page as $plkey => $pl){
			if( $plkey )	echo sprintf( '			<li><a href="%s">%s</a></li>%s' , $pl['link'] , $pl['post_title'] , PHP_EOL );
			else			echo sprintf( '			<li>%s</li>%s'  , $pl['post_title'] , PHP_EOL );
		}
	}
?>
		</ul>

		<div id="leftContents">
<?php
	//	ページ内容
	if( count( $root_page ) > 1 ){
		echo sprintf( '			<h3><img src="%simage/%s/t_%s.gif" alt="ミトシステム株式会社:%s" /></h3>' ,
						 esc_url( home_url( '/' ) ) , $_masterPage['post_name'] , $root_page[0]['post_name'] , $root_page[0]['post_title'] );
	}
	
	//	コンテンツ内容
	the_content();
	
	//	カテゴリトップのみ下層アイコン表示
	if( count( $root_page ) == 1 ){
	
		echo '			<ul class="imglist Mtw">'.PHP_EOL;
		if( is_array($_pageCategoryList) ){
			foreach($_pageCategoryList as $plkey => $pl){
				echo sprintf( '					<li><a href="%s"><img src="%simage/%s/l_%s.jpg" alt="ミトシステム株式会社：%s" /></a></li>%s' ,
							 				$pl['link'] , esc_url( home_url( '/' )) , $_masterPage['post_name'] , $pl['post_name'], $pl['post_title'], PHP_EOL );
			}
		}
		echo '			</ul>'.PHP_EOL;
	
	}
?>
		</div>
		<div id="rightContents">
			
			<div class="glayBox">
				<ul class="sideMenu">
<?php

	echo sprintf( '					<li><a href="%s" class="ti"><img src="%simage/%s/sidetitile_%s.gif" alt="%saas" /></a></li>%s' ,
					 $_masterPage['link'] , esc_url( home_url( '/' )) , $_masterPage['post_name'] , $_masterPage['post_name'] , $_masterPage['post_title'] , PHP_EOL );
	

	if( is_array($_pageCategoryList) ){
		foreach($_pageCategoryList as $plkey => $pl){
			echo sprintf( '					<li><a href="%s">%s</a></li>%s' ,
						 $pl['link'] , $pl['post_title'], PHP_EOL );
		}
	}
?>

				</ul>
			</div>
		</div>
		<br class="clear" />
	</div>
