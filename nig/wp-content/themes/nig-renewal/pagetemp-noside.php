<?php
/**
 * 固定ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $_hpRootDir, $NowLanguage;
global $post;
global $SeideMenus;

// 参照渡しで埋めるパン屑用配列を初期化
$root_page = array();

 //	パン屑リスト取得
if( is_page() ){
	ps_get_root_page( $post , $root_page );
}

$_masterPage = $root_page[count( $root_page )-1];
//	サイドバー取得
$sideMenu = get_post_meta($post->ID,'_custom_sidemenu',true);
$sideMenu = $sideMenu ? $sideMenu : 'none';
$plusMimi = '';

if( isset($SeideMenus[$sideMenu]) ){ 
	$menuColor = $SeideMenus[$sideMenu]['color'];
	$plusMimi	= $NowLanguage['now'] == 'ja' ? $SeideMenus[$sideMenu]['jp'] : $SeideMenus[$sideMenu]['name'];
	if( $plusMimi )	$plusMimi = '<li class="next">'.$plusMimi.'</li>';
}else{
	$menuColor = get_menue_color( $post->post_name );
}
//	サイドメニュー親階層設定


?>
	<div class="mainWrapp <?php echo $menuColor; ?>">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="<?php echo $menuColor; ?>">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li><?php echo $plusMimi; ?>
<?php
	if( is_array($root_page)){
		global $JumpHeadLink;
		
		foreach($root_page as $plkey => $pl){
			if( isset( $JumpHeadLink[$pl['post_name']] ))	continue;
			if( $plkey ){
				if( $pl['link'] )	echo sprintf( '			<li class="next"><a href="%s">%s</a></li>%s' , $pl['link'] , $pl['post_title'] , PHP_EOL );
				else				echo sprintf( '			<li class="next">%s</li>%s' , $pl['post_title'] , PHP_EOL );
			}
			else			echo sprintf( '			<li>%s</li>%s'  , $pl['post_title'] , PHP_EOL );
		}
	}
?>
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
				    <h2><?php the_title(); _plusTitle(); ?></h2>
                <?php endif;?>
				<div class="box">
<?php 

	the_content();

?>
				</div>
			</div>
<style>
div#rightSlot {
	width:100%;
	float:none;
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

			<br style="clear:both">
		</div>
	
	</div>

