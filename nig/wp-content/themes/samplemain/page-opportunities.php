<?php
/**
 * 固定ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $NowLanguage;
global $post , $target_category; 



######################################################################

//	カスタムフィールド読込み

$target_category = get_post_meta($post->ID, 'target_category', true);

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );


//	パン屑リスト取得
ps_get_root_page( $post , $root_page );


$_masterPage = $root_page[count( $root_page )-1];
//	サイドバー取得
$sideMenu = get_post_meta($post->ID,'_custom_sidemenu',true);
$sideMenu = $sideMenu ? $sideMenu : 'none';

if( isset($SeideMenus[$sideMenu]) ){ 
	$menuColor = $SeideMenus[$sideMenu]['color'];
}else{
	$menuColor = get_menue_color( $post->post_name );
}
//	サイドメニュー親階層設定


?>
	<div class="mainWrapp <?php echo $menuColor; ?>">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="<?php echo $menuColor; ?>">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
					<li><?php if( function_exists('RECRUITMENT_getTitle') )	echo RECRUITMENT_getTitle(0); ?></li>
				</ul>
			</div>
		</div>
	</div>


	<div id="contentsWrapper" class="mainWrapp">
		<div class="autoWrapp _<?php echo $menuColor; ?>">
		
			<div id="rightSlot">
                <?php if( get_field('title_text_hide') == 1 ) :?>
                    <!-- タイトル非表示 -->
                <?php else:?>
				    <h2><?php the_title(); ?></h2>
                <?php endif;?>
				<div class="box">
<?php 

	the_content();
	//	現在取得
	$_now =	array( ' class="_now"'  , '' );
	if( isset($_GET['end']) )	$_now =	array( '' , ' class="_now"' );
	

?>
				<p id="_selecter">
					<a<?php echo $_now[0] ; ?> href="<?php echo home_url( RECRUITMENT_PAGE_SLAG ); ?>"><?php if( function_exists('RECRUITMENT_getTitle') )	echo RECRUITMENT_getTitle(1); ?></a>
					<a<?php echo $_now[1] ; ?> href="<?php echo home_url( RECRUITMENT_PAGE_SLAG.'?end=1' ); ?>"><?php if( function_exists('RECRUITMENT_getTitle') )	echo RECRUITMENT_getTitle(2); ?></a>
				</p>
				
<?php
	if( function_exists('RECRUITMENT_getList') )		RECRUITMENT_getList( isset($_GET['end']) );
?>
				
					
				</div>
			</div>
			<br style="clear:both">
		</div>
	
	</div>
<style>

div#rightSlot {
	width:100%;
	float:none;
}
#_selecter{
	padding:0;
	margin:0;
	border-bottom: 3px solid #333;
 }
 
 #_selecter a{
	display:inline-block;
	background-color:#999;
	color:#666;
	font-size:1.2em;
	width:30%;
	text-align:center;
	padding:0.4em 0;
 }

 #_selecter a:hover{	color:#000;	 }
 #_selecter a._now{
	background-color:#333;
	color:#FFF;
 }
 #_selecter a:hover{	color:#FFF;	 }
 #_recruitList li{
 	font-size: 0.8em;
	border-bottom:1px dotted #333; 
	}
 #_recruitList li a{
 	display:block;
	padding:0.25em 0.5em;
}
 #_recruitList li a:hover{
	background-color:#EEE;
}

@media only screen and (min-width:30em) {
	div#rightSlot {
		width:99%;
	}

}
@media only screen and (min-width:60em) {
	div#rightSlot {
		width:100%;
	}

}



</style>


<?php

get_footer($NowLanguage['load']);
