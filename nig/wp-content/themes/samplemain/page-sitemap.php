<?php
/**
 * 固定ページテンプレート
 *
 * @package WordPress
 * @subpackage Trais Template
 * @since Trais Template
 */
 // ルート階層
global $_hpRootDir,$NowLanguage;

global $post;
global $SeideMenus;

// 参照渡しで埋めるパン屑用配列を初期化
$root_page = array();

 //	パン屑リスト取得
if( is_page() ){
	ps_get_root_page( $post , $root_page );
}


$_masterPage = $root_page[count( $root_page )-1];

//	共通ヘッダー読込み
get_header( $NowLanguage['load'] );
?>
	<div class="mainWrapp bgColorGl">
		<div id="bodies" class="autoWrapp">
			<div id="breadcrumb" class="bgColorGl">
				<ul>
					<li class="next"><a href="<?php echo home_url('/'); ?>">Home</a></li>
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
		<div class="autoWrapp _bgColorGl">
		
			<div id="leftSlot">
<?php
	insert_custom_tmpl();
	//	カスタムメニュー読込み
	$hdMenuOption = array(
				'theme_location'  => 'sidemenu_Sitemap',
				'menu'            => '',
				'container'       => 'div',
				'container_class' => '',
				'container_id'    => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => '',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul id="list">%3$s</ul>',
				'depth'           => 0,
				'walker'          => ''
			);
	 wp_nav_menu( $hdMenuOption );

?>
			</div>
		
			<div id="rightSlot">
                <?php if( get_field('title_text_hide') == 1 ) :?>
                    <!-- タイトル非表示 -->
                <?php else:?>
				    <h2><?php the_title();  _plusTitle(); ?></h2>
                <?php endif;?>
<?php the_content();	?>		
				
				<table width="100%">
					<td width="50%">
						<div class="box">
						<h5><a href="<?php echo $_hpRootDir; ?>">HOME</a></h5>
							<ul class="sitemap">
								<li><a href="<?php echo $_hpRootDir; ?>category/research-highlights">Research-highlights</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>research/seminer?gamen=seminar_data_list">Seminar</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>research/nig-meetings">NIG Meetings</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>category/information">Information</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>category/events">Events</a></li>
								<li><a href="https://www.facebook.com/pages/National-Institute-of-Genetics-Official/126155277477590" target="_blank">facebook</a></li>
							</ul>
					
						</div>
					</td>
<?php 
//	各種サイトマップ取得
$count = 1;
foreach( $SeideMenus as $k => $v){
	$count++;
	if( $count % 2 ){
		echo '				<tr>'.PHP_EOL;
	}
?>
					<td width="50%">
						<div class="box">
						<h5><?php echo $v['name']; ?></h5>
<?php
	//	カスタムメニュー読込み
	$smMenuOption = array(
				'theme_location'  => $k,
				'menu'            => '',
				'container'       => '',
				'container_class' => '',
				'container_id'    => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => '',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul class="sitemap">%3$s</ul>',
				'depth'           => 0,
				'walker'          => ''
			);
	wp_nav_menu( $smMenuOption );
?>
						</div>	
					</td>
<?php
	
	if( !($count % 2) ){
		echo '				</tr>'.PHP_EOL;
	}
}
?>
					</table>
				</div>
			</div>
			<br style="clear:both">
		</div>
	
	</div>


<?php get_footer($NowLanguage['load']); ?>