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
						<h5><a href="<?php echo $_hpRootDir; ?>ja/">ホーム</a></h5>
							<ul class="sitemap">
								<li><a href="<?php echo $_hpRootDir; ?>ja/category/research-highlights_ja">Research Highlights</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>ja/seminer-ja?gamen=seminar_data_list">セミナー</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>ja/reserach/nig-meetings">研究会</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>ja/category/information_ja">インフォメーション</a></li>
								<li><a href="<?php echo $_hpRootDir; ?>ja/category/events_ja">イベント</a></li>
								<li><a href="https://www.facebook.com/pages/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/242392782455048" target="_blank">facebook</a></li>
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
						<h5><?php echo $v['jp']; ?></h5>
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